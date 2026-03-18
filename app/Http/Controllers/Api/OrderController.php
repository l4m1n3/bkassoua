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
use Exception;


class OrderController extends Controller
{

public function index()
{
    $regions = DeliveryRegion::select('id', 'name', 'fee')->get();
    return response()->json($regions);
}
// public function placeOrder(Request $request)
// {
//     // Vérifier l'authentification
//     $user = Auth::guard('sanctum')->user();
//     if (!$user) {
//         return response()->json([
//             'success' => false,
//             'error' => 'Utilisateur non authentifié',
//         ], 401);
//     }

//     // Valider les données de la requête
//     $validator = Validator::make($request->all(), [
//         'payment_method' => 'required|string',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'success' => false,
//             'error' => 'Validation échouée',
//             'details' => $validator->errors(),
//         ], 422);
//     }

//     // Charger le panier
//     $carts = Cart::with('product')->where('user_id', $user->id)->get();
//     if ($carts->isEmpty()) {
//         return response()->json([
//             'success' => false,
//             'error' => 'Votre panier est vide',
//         ], 400);
//     }

//     // Vérifier l'existence des produits
//     foreach ($carts as $cart) {
//         if (!$cart->product) {
//             return response()->json([
//                 'success' => false,
//                 'error' => 'Un produit dans votre panier n\'existe plus',
//             ], 400);
//         }
//         if ($cart->quantity > $cart->product->stock_quantity) {
//                 throw new Exception("Stock insuffisant pour {$cart->product->name}");
//             }
//             $cart->product->decrement('stock_quantity', $cart->quantity);
//     }

//     // Calcul du total produits
//     $totalAmount = $carts->sum(function ($cart) {
//         return $cart->product->price * $cart->quantity;
//     });
    
//   $region = DeliveryRegion::where('name', $request->address)->first();

//     if (!$region) {
//         return response()->json([
//             'success' => false,
//             'error' => 'Région de livraison non trouvée'
//         ], 404);
//     }
    
//     $delivery_fee = $region->fee;
//     // Calcul total frais de livraison (par produit)
//     // $totalDeliveryFee = $carts->sum(function ($cart) {
//     //     return ($cart->product->delivery_fee ?? 0) * $cart->quantity;
//     // });
//  $totalAmounts= $totalAmount+$delivery_fee;
//     DB::beginTransaction();

//     try {
//         // Création de la commande
//         $order = Order::create([
//             'user_id' => $user->id,
//             'total_amount' => $totalAmount,
//             'delivery_fee' => $totalDeliveryFee,
//         ]);

//         // Création du paiement
//         $payment = Payment::create([
//             'order_id' => $order->id,
//             'amount' => $totalAmounts,
//             'status' => 'pending',
//             'payment_method' => $request->payment_method,
//             'transaction_id' => Str::uuid(),
//         ]);

//         // Ajout des articles
//         $productNames = [];
//         foreach ($carts as $cart) {
//             OrderItem::create([
//                 'order_id' => $order->id,
//                 'product_id' => $cart->product->id,
//                 'quantity' => $cart->quantity,
//                 'price' => $cart->product->price,
//             ]);
//             $productNames[] = $cart->product->name;
//         }

//         // Vider le panier
//         Cart::where('user_id', $user->id)->delete();

//         // Notification interne
//         $productsList = implode(', ', $productNames);
//         Notification::create([
//             'user_id' => $user->id,
//             'title' => 'Nouvelle commande',
//             'message' => "Vous avez commandé : $productsList. Paiement en attente.",
//             'type' => 'order',
//             'read' => false,
//         ]);

//         DB::commit();

//         // 📩 Envoi SMS de confirmation
//         try {
//             $phone = $user->phone_number; // Assure-toi que le champ existe dans ton modèle User
//             $totalDeliveryFee=1000;
//             $message = "Cher client, votre commande #{$order->id} a été enregistrée et est en cours de préparation. Montant total (produits + livraison) : " . number_format($totalAmount + $totalDeliveryFee, 0, ',', ' ') . " FCFA.";

//             $response = Http::withBasicAuth(env('SMS_API_USERNAME'), env('SMS_API_PASSWORD'))
//                 ->post(env('SMS_API_URL'), [
//                     'to' => '227' . $phone,
//                     'from' => env('SMS_SENDER'),
//                     'content' => $message,
//                     'dlr' => 'yes',
//                     'dlr-level' => 3,
//                     'dlr-method' => 'GET',
//                     'dlr-url' => env('SMS_DLR_URL'),
//                 ]);

//             Log::info('SMS Order Confirmation', [
//                 'phone_number' => $phone,
//                 'order_id' => $order->id,
//                 'response_status' => $response->status(),
//                 'response_body' => $response->body(),
//             ]);

//             if (!$response->successful()) {
//                 Log::error("Échec d'envoi du SMS pour la commande {$order->id}");
//             }
//         } catch (\Exception $e) {
//             Log::error("Erreur lors de l'envoi du SMS pour la commande {$order->id}", [
//                 'error' => $e->getMessage()
//             ]);
//         }

//         // Réponse JSON
//         return response()->json([
//             'success' => true,
//             'message' => 'Commande passée avec succès. Paiement en attente.',
//             'data' => [
//                 'order_id' => $order->id,
//                 'total_amount' => $totalAmounts,
//                 'payment_method' => $payment->payment_method,
//                 'status' => $order->status,
//             ],
//         ], 201);

//     } catch (\Exception $e) {
//         DB::rollBack();
//         Log::error('Erreur lors de la commande : ' . $e->getMessage(), [
//             'user_id' => $user->id,
//             'exception' => $e,
//         ]);

//         return response()->json([
//             'success' => false,
//             'error' => 'Une erreur est survenue lors du traitement de la commande',
//             'details' => env('APP_DEBUG', false) ? $e->getMessage() : null,
//         ], 500);
//     }
// }

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
