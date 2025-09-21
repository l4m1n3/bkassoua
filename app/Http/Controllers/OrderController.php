<?php

namespace App\Http\Controllers;

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
    public function showCheckout()
    {
        $categories = Category::all();
        return view('shop.checkout', compact('categories'));
    }
    // Afficher les commandes reçues par le vendeur
    public function index()
    {
        $orders = Order::where('vendor_id', Auth::user()->vendor->id)->get();

        return view('vendor.orders.index', compact('orders'));
    }
 
    // Voir les détails d'une commande
    // public function show(Order $order)
    // {
    //     $this->authorize('view', $order);  // Vérification si le vendeur a accès à la commande

    //     $orderProducts = $order->products;

    //     return view('vendor.orders.show', compact('order', 'orderProducts'));
    // }

    // Mettre à jour le statut d'une commande
    // public function updateStatus(Request $request, Order $order)
    // {
    //     $this->authorize('update', $order);  // Vérification si le vendeur peut modifier la commande

    //     $order->update([
    //         'status' => $request->status,
    //     ]);

    //     return redirect()->route('vendor.orders.index')->with('success', 'Statut de la commande mis à jour avec succès.');
    // }
    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $request->total_price,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($request->items as $item) {
            $quantity=$item['quantity'];
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
            // $user = User::where('id',Auth::user()->id)->update(['role'=>'vendor']);
           $product= Product::where('id', $item['product_id'])->update(['stock_quantity'=>--$quantity]);
        }

        return response()->json(['message' => 'Commande créée avec succès.', 'order' => $order]);
    }

    // public function placeOrder()
    // {
    //     $user = Auth::user();
    //     $carts = Cart::with('product')->where('user_id', $user->id)->get();

    //     if ($carts->isEmpty()) {
    //         return redirect()->back()->with('error', 'Votre panier est vide.');
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
    //         'user_id' => $order->user_id,
    //         'title' => 'Nouvelle commande',
    //         'message' => "Vous avez commandé le produit {$order->product->name}.",
    //         'type' => 'order',
    //         'read' => false,
    //     ]);

    //     return redirect()->route('shop', $order->id)->with('success', 'Commande passée avec succès.');
    // }
   
    // public function placeOrder(Request $request)
    // {
    //     $user = Auth::user();
    //     $carts = Cart::with('product')->where('user_id', $user->id)->get();

    //     if ($carts->isEmpty()) {
    //         return redirect()->back()->with('error', 'Votre panier est vide.');
    //     }

    //     // Vérifier que tous les produits existent
    //     foreach ($carts as $cart) {
    //         if (!$cart->product) {
    //             return redirect()->back()->with('error', 'Un produit dans votre panier n\'existe plus.');
    //         }
    //     }

    //     // Calcul du total
    //     $totalAmount = $carts->sum(function ($cart) {
    //         return $cart->product->price * $cart->quantity;
    //     });

    //     try {
    //         // Créer la commande
    //         $order = Order::create([
    //             'user_id' => $user->id,
    //             'total_amount' => $totalAmount,
    //             'status' => 'pending',
    //         ]);

    //         // Ajouter les produits à la commande
    //         $productNames = []; // Pour lister les noms des produits dans la notification
    //         foreach ($carts as $cart) {
    //             OrderItem::create([
    //                 'order_id' => $order->id,
    //                 'product_id' => $cart->product->id,
    //                 'quantity' => $cart->quantity,
    //                 'price' => $cart->product->price,
    //             ]);
    //             $productNames[] = $cart->product->name; // Ajouter le nom du produit
    //         }

    //         // Vider le panier
    //         Cart::where('user_id', $user->id)->delete();

    //         // Créer une notification pour l'acheteur
    //         $productsList = implode(', ', $productNames); // Concaténer les noms des produits
    //         Notification::create([
    //             'user_id' => $user->id, // L'acheteur
    //             'title' => 'Nouvelle commande',
    //             'message' => "Vous avez commandé : $productsList.",
    //             'type' => 'order',
    //             'read' => false,
    //         ]);

    //         return redirect()->route('shop', ['order' => $order->id])
    //                         ->with('success', 'Commande passée avec succès.');

    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
    //     }
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
