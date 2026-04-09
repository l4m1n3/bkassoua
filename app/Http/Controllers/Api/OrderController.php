<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\DeliveryRegion;
use App\Models\AttributeOptions;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use App\Events\NewOrderCreated;
use Exception;


class OrderController extends Controller
{

    public function index()
    {
        $regions = DeliveryRegion::select('id', 'name', 'fee')->get();
        return response()->json($regions);
    }
    public function placeOrder(Request $request)
    {
        // Vérifier l'authentification
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'Utilisateur non authentifié'
            ], 401);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'payment_method'             => 'required|string',
            'region_id'                  => 'required|exists:delivery_regions,id',
            // ✅ Ajout validation options
            'items'                      => 'nullable|array',
            'items.*.product_id'         => 'required_with:items|exists:products,id',
            'items.*.selected_options'   => 'nullable|array',
            'items.*.selected_options.*' => 'integer|exists:attribute_options,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation échouée',
                'details' => $validator->errors(),
            ], 422);
        }

        // Charger panier
        $carts = Cart::with('product')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return response()->json([
                'success' => false,
                'error' => 'Votre panier est vide'
            ], 400);
        }

        // Vérifier produits
        foreach ($carts as $cart) {

            if (!$cart->product) {
                return response()->json([
                    'success' => false,
                    'error' => 'Un produit dans votre panier n\'existe plus'
                ], 400);
            }

            if ($cart->quantity > $cart->product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'error' => "Stock insuffisant pour {$cart->product->name}"
                ], 400);
            }
        }

        // Calcul total produits
        $totalAmount = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        // Livraison par région
        $region = DeliveryRegion::find($request->region_id);

        if (!$region) {
            return response()->json([
                'success' => false,
                'error' => 'Région de livraison non trouvée'
            ], 404);
        }

        $totalDeliveryFee = $region->fee;
        $totalAmounts     = $totalAmount + $totalDeliveryFee;

        // ✅ Indexer les options choisies par product_id
        $itemsOptions = collect($request->items ?? [])->keyBy('product_id');

        DB::beginTransaction();

        try {

            // Création commande
            $order = Order::create([
                'user_id'      => $user->id,
                'total_amount' => $totalAmount,
                'delivery_fee' => $totalDeliveryFee,
                'status'       => 'pending'
            ]);

            // Création paiement
            $payment = Payment::create([
                'order_id'       => $order->id,
                'amount'         => $totalAmounts,
                'status'         => 'pending',
                'payment_method' => $request->payment_method,
                'transaction_id' => Str::uuid(),
            ]);

            $productNames  = [];
            $orderItemsLog = []; // ✅ Pour la réponse JSON

            foreach ($carts as $cart) {

                // ✅ Résoudre les options choisies pour ce produit
                $selectedOptions = null;

                if ($itemsOptions->has($cart->product_id)) {
                    $rawOptionIds = $itemsOptions[$cart->product_id]['selected_options'] ?? [];

                    if (!empty($rawOptionIds)) {
                        // Valider que les options appartiennent bien à la sous-catégorie du produit
                        $validOptions = AttributeOptions::with('attribute')
                            ->whereIn('id', $rawOptionIds)
                            ->whereHas('attribute', function ($q) use ($cart) {
                                $q->where('sous_cat_id', $cart->product->sous_cat_id);
                            })
                            ->get();

                        if ($validOptions->isNotEmpty()) {
                            // Stocker lisiblement : {"Couleur": "Rouge", "Taille": "M"}
                            $selectedOptions = $validOptions->mapWithKeys(fn($opt) => [
                                $opt->attribute->name => $opt->value
                            ])->toArray();
                        }
                    }
                }

                // Réduire le stock
                $cart->product->decrement('stock_quantity', $cart->quantity);

                // ✅ Créer order item avec options
                OrderItem::create([
                    'order_id'         => $order->id,
                    'product_id'       => $cart->product->id,
                    'quantity'         => $cart->quantity,
                    'price'            => $cart->product->price,
                    'selected_options' => $selectedOptions, // colonne JSON nullable
                ]);

                $productNames[]  = $cart->product->name;

                // ✅ Log pour la réponse
                $orderItemsLog[] = [
                    'product'          => $cart->product->name,
                    'quantity'         => $cart->quantity,
                    'price'            => $cart->product->price,
                    'selected_options' => $selectedOptions ?? [],
                ];
            }

            // Vider panier
            Cart::where('user_id', $user->id)->delete();

            // Notification
            Notification::create([
                'user_id' => $user->id,
                'title'   => 'Nouvelle commande',
                'message' => "Vous avez commandé : " . implode(', ', $productNames) . ". Paiement en attente.",
                'type'    => 'order',
                'read'    => false,
            ]);

            DB::commit();
            // 🔔 Broadcast temps réel vers le dashboard admin
            $order->load('orderItems', 'user');
            event(new NewOrderCreated($order));
            // SMS confirmation
            try {

                $phone = $user->phone_number;

                $message = "Cher client, votre commande #{$order->id} a été enregistrée. Montant total (produits + livraison) : " .
                    number_format($totalAmounts, 0, ',', ' ') . " FCFA.";

                $response = Http::withBasicAuth(
                    env('SMS_API_USERNAME'),
                    env('SMS_API_PASSWORD')
                )->post(env('SMS_API_URL'), [
                    'to'         => '227' . $phone,
                    'from'       => env('SMS_SENDER'),
                    'content'    => $message,
                    'dlr'        => 'yes',
                    'dlr-level'  => 3,
                    'dlr-method' => 'GET',
                    'dlr-url'    => env('SMS_DLR_URL'),
                ]);

                Log::info('SMS envoyé', [
                    'order_id' => $order->id,
                    'phone'    => $phone,
                    'status'   => $response->status(),
                    'body'     => $response->body()
                ]);
            } catch (\Exception $e) {

                Log::error("Erreur SMS commande {$order->id}", [
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Commande passée avec succès',
                'data'    => [
                    'order_id'       => $order->id,
                    'products_total' => $totalAmount,
                    'delivery_fee'   => $totalDeliveryFee,
                    'total_amount'   => $totalAmounts,
                    'payment_method' => $payment->payment_method,
                    'status'         => $order->status,
                    'items'          => $orderItemsLog, // ✅ Ajout
                ]
            ], 201);
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Erreur commande', [
                'user_id' => $user->id,
                'error'   => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error'   => 'Erreur lors du traitement de la commande'
            ], 500);
        }
    }
}
