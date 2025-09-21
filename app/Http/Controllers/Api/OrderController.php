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
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // public function placeOrder(Request $request)
    // {
    //     $user = Auth::user();
    //     $carts = Cart::with('product')->where('user_id', $user->id)->get();

    //     if ($carts->isEmpty()) {
    //         return response()->json(['error' => 'Votre panier est vide.'], 400);
    //     }

    //     // Calcul du total
    //     $totalAmount = $carts->sum(function ($cart) {
    //         return $cart->product->price * $cart->quantity;
    //     });

    //     // Créer la commande
    //     $order = Order::create([
    //         'user_id' => $user->id,
    //         'total_amount' => $totalAmount,
    //         'status' => 'pending',
    //         'address' => $request->input('address', 'Adresse par défaut'),
    //         'payment_method' => $request->input('payment_method', 'cash_on_delivery'),
    //     ]);

    //     // Ajouter les produits à la commande
    //     foreach ($carts as $cart) {
    //         OrderItem::create([
    //             'order_id' => $order->id,
    //             'product_id' => $cart->product->id,
    //             'quantity' => $cart->quantity,
    //             'price' => $cart->product->price,
    //         ]);
    //     }

    //     // Vider le panier après la commande
    //     Cart::where('user_id', $user->id)->delete();
    //     Notification::create([
    //         'user_id' => $order->seller_id,
    //         'title' => 'Nouvelle commande',
    //         'message' => "Vous avez reçu une nouvelle commande pour {$order->product->name}.",
    //         'type' => 'order',
    //         'read' => false,
    //     ]);

    //     // return response()->json(['message' => 'Commande créée avec succès', 'order' => $order], 201);
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Commande passée avec succès.',
    //         'order_id' => $order->id,
    //         'order' => $order,
    //         'total_amount' => $totalAmount,
    //     ], 201);
    // }
    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        $carts = Cart::with('product')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return redirect()->back()->with('error', 'Votre panier est vide.');
        }

        // Vérification des produits
        foreach ($carts as $cart) {
            if (!$cart->product) {
                return redirect()->back()->with('error', 'Un produit dans votre panier n\'existe plus.');
            }
        }

        // Calcul du total
        $totalAmount = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });

        // Démarrer une transaction
        DB::beginTransaction();

        try {
            // Créer la commande
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
            ]);

            // Créer le paiement associé
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'status' => 'pending', // ou 'completed' selon votre flux
                'payment_method' => $request->payment_method ?? 'unknown',
                'transaction_id' => Str::uuid(), // ou un ID de transaction réel
            ]);

            // Ajouter les produits à la commande
            $productNames = [];
            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product->id,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                ]);
                $productNames[] = $cart->product->name;
            }

            // Vider le panier
            Cart::where('user_id', $user->id)->delete();

            // Notifications
            $productsList = implode(', ', $productNames);
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Nouvelle commande',
                'message' => "Vous avez commandé : $productsList. Paiement en attente.",
                'type' => 'order',
                'read' => false,
            ]);

            // Notifier l'admin si nécessaire
            // ...

            // Valider la transaction
            DB::commit();

            return redirect()->route('order.confirmation', ['order' => $order->id])
                ->with('success', 'Commande passée avec succès. Paiement en attente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la commande : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }
}
