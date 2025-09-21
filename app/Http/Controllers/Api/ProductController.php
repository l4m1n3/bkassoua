<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\OrderItem;
use App\Models\Order;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Vérifier si un category_id est fourni dans la requête
        $categoryId = $request->query('category_id');

        // Récupérer les produits avec leurs vendeurs et catégories associés
        $query = Product::with(['vendor', 'category']);

        // Filtrer par catégorie si un ID est fourni
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Paginer les résultats à 10 par page
        $products = $query->get();

        // Retourner les produits en JSON
        return response()->json([
            'products' => $products,
        ], 200);
    }

    public function show(Request $request, $productId)
    {
        // Récupérer le produit spécifique en fonction de son ID
        $product = Product::with(['vendor', 'category'])->find($productId);

        // Vérifier si le produit existe
        if (!$product) {
            return response()->json([
                'message' => 'Produit non trouvé.'
            ], 404);
        }

        // Retourner le produit en réponse JSON
        return response()->json([
            'product' => $product,
        ], 200);
    }

    public function getProducts($vendorId)
    {
        try {
            $vendor = Vendor::with('products')->find($vendorId);

            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendeur non trouvé.',
                ], 404);
            }

            $products = $vendor->products;

            if ($products->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun produit trouvé pour ce vendeur.',
                    'products' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Produits récupérés avec succès.',
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite : ' . $e->getMessage()
            ], 500);
        }
    }


    // Ajouter un produit
    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|integer',
            'vendor_id' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Sauvegarder l'image
        $imagePath = $request->file('image')->store('products', 'public');

        // Créer le produit
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'vendor_id' => $request->vendor_id,
            'image' => $imagePath,
        ]);

        return response()->json($product, 201);
    }
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produit introuvable'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Produit supprimé avec succès'], 200);
    }
    public function getPopularProduct()
    {
        $popularProducts = OrderItem::with(['product'])->get();
        // dd($popularProducts);
        return response()->json([

            'popularProducts' => $popularProducts
        ], 200);
    }

    public function getHistory($userId)
    {
        // Récupérer les IDs des commandes passées par l'utilisateur
        $orderIds = Order::where('user_id', $userId)->pluck('id');

        // Récupérer les produits associés à ces commandes
        $history = Product::whereIn('id', OrderItem::whereIn('order_id', $orderIds)->pluck('product_id'))
            ->distinct()
            ->get();

        return response()->json([
            'history' => $history
        ], 200);
    }

    public function getHistory($userId)
    {
        // Récupérer les commandes de l'utilisateur
        $orders = Order::where('user_id', $userId)
            ->with(['orderItems.product.category']) // Charger les relations
            ->select('id', 'status', 'created_at')
            ->get()
            ->map(function ($order) {
                return [
                    'order_id' => $order->id,
                    'status' => $order->status, // 'pending', 'confirmed', 'delivered'
                    'created_at' => $order->created_at->toDateTimeString(),
                    'products' => $order->orderItems->map(function ($item) {
                        return [
                            'product_id' => $item->product->id,
                            'product_name' => $item->product->name,
                            'category' => $item->product->category ? $item->product->category->name : null,
                            'quantity' => $item->quantity,
                        ];
                    })->toArray(),
                ];
            });

        return response()->json([
            'orders' => $orders
        ], 200);
    }

    public function getNewProduct()
    {
        // Obtenir la date de début de la semaine
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Requête pour les produits ajoutés cette semaine
        $productsThisWeeks = Product::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
        return response()->json([

            'newProducts' => $productsThisWeeks
        ], 200);
    }
}
