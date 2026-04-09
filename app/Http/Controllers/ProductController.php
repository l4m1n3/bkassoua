<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Vendor;
use App\Models\Attribute;
use App\Models\AttributeOptions;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class ProductController extends Controller
{

    public function updateCart(Request $request, $cartId)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:100'
            ]);

            $cartItem = auth()->user()->cartItems()
                ->where('id', $cartId)
                ->firstOrFail();

            // Vérification du stock
            if ($request->quantity > $cartItem->product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuffisant ! Seulement ' . $cartItem->product->stock_quantity . ' disponible(s).'
                ], 400);
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            // Nombre d'articles dans le panier
            $cartCount = auth()->user()->cartItems()->sum('quantity');

            // Total du panier (prix réel du produit)
            $cartTotal = auth()->user()->cartItems()
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->sum(\DB::raw('carts.quantity * products.price'));

            return response()->json([
                'success' => true,
                'message' => 'Panier mis à jour !',
                'cart_count' => $cartCount,
                'cart_total' => $cartTotal
            ]);
        } catch (\Throwable $th) {
            Log::error('Erreur lors de la mise à jour du panier : ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du panier.'
            ], 500);
        }
    }
    public function index(Request $request)
    {
        $query = Product::with([
            'vendor',
            'sousCat',                    // Assure-toi que c'est bien sousCat (relation)
            'attributeValues.attributeOption.attribute'
        ]);

        // 🔍 Recherche par nom
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 📂 Catégorie (sous-catégorie)
        if ($request->filled('category')) {
            $query->where('sous_cat_id', $request->category);
        }

        // 👁️ Visibilité
        if ($request->filled('visible')) {
            $query->where('is_visible', $request->visible);
        }

        // 📦 Stock (filtre rapide)
        if ($request->filled('stock')) {
            if ($request->stock === 'in_stock') {
                $query->where('stock_quantity', '>', 3);
            } elseif ($request->stock === 'low_stock') {
                $query->whereBetween('stock_quantity', [1, 3]);
            } elseif ($request->stock === 'out_of_stock') {
                $query->where('stock_quantity', 0);
            }
        }

        // Filtres avancés
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('min_stock')) {
            $query->where('stock_quantity', '>=', $request->min_stock);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $products = $query->paginate(10)->withQueryString();

        // Statistiques globales (non filtrées)
        $totalProducts     = Product::count();
        $visibleProducts   = Product::where('is_visible', 1)->count();
        $lowStockProducts  = Product::whereBetween('stock_quantity', [1, 3])->count();
        $outOfStockProducts = Product::where('stock_quantity', 0)->count();

        $categories = Category::with('sousCat')->get();   // Ou SousCategory si tu as un model séparé
        $vendors    = Vendor::all();
        $attributes = Attribute::with('options')->get();

        return view('admin.product', compact(
            'products',
            'categories',
            'vendors',
            'attributes',
            'totalProducts',
            'visibleProducts',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        dd($validated);
        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produit ajouté avec succès');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour avec succès');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé avec succès');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $products = Product::with(['vendor', 'category'])
            ->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('description', 'LIKE', '%' . $search . '%')
            ->paginate(10);

        return view('admin.product', compact('products'));
    }
    public function shop()
    {
        $categories = Category::all();
        $products = Product::with(['images', 'mainImage', 'vendor', 'sousCat'])->get();
        return view('shop.shop', compact('products', 'categories'));
    }
    public function productPerCategory($categorySlug)
    {
        // Retrieve all categories for display
        $categories = Category::all();

        // Fetch products filtered by the category slug
        $products = Product::with(['vendor', 'sousCat'])
            ->whereHas('sousCat', function ($query) use ($categorySlug) {
                $query->where('slug', 'LIKE', '%' . $categorySlug . '%');
            })
            ->paginate(10);

        // Return the view with the data
        return view('shop.shop', compact('products', 'categories'));
    }

    public function productDetail($id)
    {
        // Retrieve all categories for display
        $categories = Category::all();
        $product = Product::with(['vendor', 'sousCat'])->find($id);
        return view('shop.detail', compact('product', 'categories'));
    }
    // public function storeCart(Request $request)
    // {
    //     $user_id = Auth::id();

    //     // Validate the input
    //     $validated = $request->validate([
    //         'quantity' => 'required|numeric|min:1',
    //         'product_id' => 'required|numeric|exists:products,id',
    //     ]);
    //     // dd($validated);
    //     // Fetch the product
    //     $product = Product::findOrFail($request->product_id);

    //     // Check stock availability
    //     if ($request->quantity > $product->stock_quantity) {
    //         return redirect()->back()->with('error', 'La quantité demandée dépasse le stock disponible.');
    //     }

    //     // Check if the product is already in the cart
    //     $existingCart = Cart::where('user_id', $user_id)
    //         ->where('product_id', $request->product_id)
    //         ->first();

    //     if ($existingCart) {
    //         // Update the quantity
    //         $existingCart->quantity += $request->quantity;

    //         // Ensure updated quantity doesn't exceed stock
    //         if ($existingCart->quantity > $product->stock_quantity) {
    //             return redirect()->back()->with('error', 'La quantité totale dépasse le stock disponible.');
    //         }

    //         $existingCart->save();
    //     } else {
    //         // Create a new cart entry

    //         Cart::create([
    //             'user_id' => Auth::id(),
    //             'quantity' => $request->quantity,
    //             'product_id' => $request->product_id,
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Produit ajouté avec succès au panier.');
    // }
    public function storeCart(Request $request)
    {
        $user_id = Auth::id();

        // Vérifier si l'utilisateur est connecté
        if (!$user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour ajouter un produit au panier.'
            ], 401);
        }

        // Validation
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:1',
            'product_id' => 'required|numeric|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => 'La quantité demandée dépasse le stock disponible.'
            ]);
        }

        $existingCart = Cart::where('user_id', $user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingCart) {
            $existingCart->quantity += $request->quantity;

            if ($existingCart->quantity > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'La quantité totale dépasse le stock disponible.'
                ]);
            }

            $existingCart->save();
        } else {
            Cart::create([
                'user_id' => $user_id,
                'quantity' => $request->quantity,
                'product_id' => $request->product_id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté avec succès au panier.',
            // Facultatif : renvoyer le nouveau total du panier
            'cart_count' => Cart::where('user_id', $user_id)->count()
        ]);
    }

    // app/Http/Controllers/CartController.php
    // public function updateCart(Request $request, Cart $cart)
    // {
    //     // $request->validate([
    //     //     'quantity' => 'required|integer|min:1'
    //     // ]);

    //     try {
    //         // Vérifier que l'article appartient à l'utilisateur connecté
    //         if ($cart->user_id !== auth()->id()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Non autorisé'
    //             ], 403);
    //         }

    //         // Vérifier le stock disponible
    //         if ($request->quantity > $cart->product->stock_quantity) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Stock insuffisant'
    //             ], 400);
    //         }

    //         // Mettre à jour la quantité
    //         $cart->update([
    //             'quantity' => $request->quantity
    //         ]);

    //         // Recalculer les totaux
    //         $carts = Cart::where('user_id', auth()->id())->with('product')->get();
    //         $total = $carts->sum(function ($cart) {
    //             return $cart->product->price * $cart->quantity;
    //         });

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Quantité mise à jour',
    //             'cart' => $cart,
    //             'item_total' => $cart->product->price * $cart->quantity,
    //             'subtotal' => $total,
    //             'grand_total' => $total >= 50000 ? $total : $total + 1000,
    //             'free_shipping' => $total >= 50000
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function showCart()
    {
        $categories = Category::all();
        $user = Auth::user()->id; // Récupère l'utilisateur connecté

        $carts = Cart::with(['product', 'user'])
            ->where('user_id', $user) // Filtrer les paniers pour l'utilisateur connecté
            ->get();
        // Calculer le montant total
        $total = $carts->sum(function ($cart) {
            return $cart->product->price * $cart->quantity;
        });
        return view('cart.show', compact('carts', 'categories', 'total'));
    }

    public function removeCart(Request $request)
    {
        $user_id = Auth::id(); // récupère l'utilisateur connecté
        $product_id = $request->product_id; // récupère le produit à supprimer

        // Cherche l'élément du panier correspondant à l'utilisateur et au produit
        $cart = Cart::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($cart) {
            $cart->delete();
            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé du panier avec succès.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produit introuvable dans le panier.'
            ]);
        }
    }
}
