<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Vérifier si un category_id est fourni dans la requête
        $sous_cat_id = $request->query('sous_cat_id');

        // Récupérer les produits avec leurs vendeurs et catégories associés
        $query = Product::with(['vendor', 'sousCat']);

        if ($sous_cat_id) {
            $query->where('sous_cat_id', $sous_cat_id);
        }
        $products = $query->get();

        // Retourner les produits en JSON
        return response()->json([
            'products' => $products,
        ], 200);
    }

    public function show(Request $request, $productId)
    {
        // Récupérer le produit spécifique en fonction de son ID
        $product = Product::with(['vendor', 'sousCat'])->find($productId);

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
            $vendor = Vendor::with('products.images')->find($vendorId);

            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendeur non trouvé.',
                ], 404); // ✅ 404 OK ici car le vendeur n'existe pas
            }

            $products = $vendor->products;

            // ✅ CORRECTION : Retourner 200 avec tableau vide au lieu de 404
            if ($products->isEmpty()) {
                return response()->json([
                    'success' => true, // ⚠️ Changé en true
                    'message' => 'Aucun produit trouvé pour ce vendeur.',
                    'products' => []
                ], 200); // ⚠️ Changé en 200
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
            'sous_cat_id' => 'required|integer',
            // 'vendor_id' => 'required|integer',
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        $vendor = auth()->user()->vendor;
        // Sauvegarder l'image
        // $imagePath = $request->file('image')->store('products', 'public');
        DB::beginTransaction();
        // Créer le produit
        try {
            $product = Product::create([
                'vendor_id' => $vendor->id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'is_active' => $request->is_active,
                'sous_cat_id' => $request->sous_cat_id,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $product->images()->create([
                        'path' => $image->store('products', 'public'),
                        'is_main' => $index === 0, // la 1ère image est principale
                    ]);
                }
            }
            // ✅ Charger les relations pour le retour
            $product->load('images');
            DB::commit();
            return response()->json($product, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erreur lors de la création du produit: ' . $e->getMessage()], 500);
        }
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
    // Récupérer les produits populaires
    public function getPopularProduct()
    {
        $popularProducts = OrderItem::with(['product'])->get();
        return response()->json([
            'popularProducts' => $popularProducts
        ], 200);
    }
    public function getPopularCategory()
    {
        $popularCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'popularCategories' => $popularCategories
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

    // public function getHistory($userId)
    // {
    //     // Récupérer les commandes de l'utilisateur
    //     $orders = Order::where('user_id', $userId)
    //         ->with(['orderItems.product.category']) // Charger les relations
    //         ->select('id', 'status', 'created_at')
    //         ->get()
    //         ->map(function ($order) {
    //             return [
    //                 'order_id' => $order->id,
    //                 'status' => $order->status, // 'pending', 'confirmed', 'delivered'
    //                 'created_at' => $order->created_at->toDateTimeString(),
    //                 'products' => $order->orderItems->map(function ($item) {
    //                     return [
    //                         'product_id' => $item->product->id,
    //                         'product_name' => $item->product->name,
    //                         'category' => $item->product->category ? $item->product->category->name : null,
    //                         'quantity' => $item->quantity,
    //                     ];
    //                 })->toArray(),
    //             ];
    //         });

    //     return response()->json([
    //         'orders' => $orders
    //     ], 200);
    // }

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
    // Produits similaires
    public function similarProducts(int $productId)
    {
        $product = Product::find($productId);
        // dd($product);
        if (!$product) {
            return response()->json([
                'message' => 'Produit introuvable'
            ], 404);
        }

        $similarProducts = Product::query()
            ->where('sous_cat_id', $product->sous_cat_id)
            ->where('id', '!=', $product->id)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        // dd($similarProducts);
        return response()->json([
            'data' => $similarProducts
        ], 200);
    }
}
