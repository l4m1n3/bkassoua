<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controller\Api\ProductController;
use App\models\Cart;
use App\models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);

        // Vérifier si le stock est suffisant
        if ($product->stock_quantity < $request->quantity) {
            return response()->json(['message' => 'Stock insuffisant'], 400);
        }
        $user = Auth::user(); // Récupère l'utilisateur connecté

        if (!$user) {
            return response()->json(["error" => "Utilisateur non authentifié"], 401);
        }
        // Ajouter au panier (simple session ou base de données)
        $cartItem = Cart::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $request->product_id,
            ],
            [
                'quantity' => \DB::raw('quantity + ' . $request->quantity),
            ]
        );

        return response()->json([
            'message' => 'Produit ajouté au panier avec succès',
            'cart_item' => $cartItem,
        ], 201);
    }

    public function cart()
    {
        // Récupérer l'ID de l'utilisateur authentifié
        $userId = Auth::id();

        // Récupérer les articles du panier de l'utilisateur
        $cartItems = Cart::where('user_id', $userId)->get();

        // Retourner les données du panier en format JSON
        return response()->json($cartItems);
    }
    public function cartDelete($id)
    {
        // Récupérer l'ID de l'utilisateur authentifié
        $userId = Auth::id();

        // Récupérer les articles du panier de l'utilisateur
        $cartItems = Cart::where('user_id', $userId)->get();

        // Retourner les données du panier en format JSON
        return response()->json($cartItems);
    }
}
