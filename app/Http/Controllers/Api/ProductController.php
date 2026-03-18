<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\SousCat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // public function index(Request $request)
    // {

    //     $query = Product::with(['vendor', 'sousCat']);

    //     // Filtrer par catégorie si fourni
    //     if ($subCategoryId = $request->query('sous_cat_id')) {
    //         $query->where('sous_cat_id', $subCategoryId);
    //     }

    //     // Filtrer par mot-clé de recherche
    //     if ($search = $request->query('search')) {
    //         $query->where('name', 'LIKE', "%{$search}%")
    //               ->orWhere('description', 'LIKE', "%{$search}%");
    //     }

    //     // Paginer les résultats (10 par page)
    //     $products = $query->paginate(10);

    //     // Ajouter l'URL complète pour les images
    //     $products->getCollection()->transform(function ($product) {
    //         $product->image_url = $product->image ? url('storage/' . $product->image) : null;
    //         return $product;
    //     });

    //     return response()->json([
    //         'success' => true,
    //         'products' => $products->items(),
    //         'pagination' => [
    //             'total' => $products->total(),
    //             'per_page' => $products->perPage(),
    //             'current_page' => $products->currentPage(),
    //             'last_page' => $products->lastPage(),
    //         ],
    //     ], 200);
    // }
    public function index(Request $request)
    {
        $query = Product::with([
            'vendor',
            'sousCat',
            'sousCat.attributes.options', // attributs + options via la sous-catégorie
        ]);

        if ($subCategoryId = $request->query('sous_cat_id')) {
            $query->where('sous_cat_id', $subCategoryId);
        }

        if ($search = $request->query('search')) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
        }

        $products = $query->paginate(10);

        $products->getCollection()->transform(function ($product) {
            $product->image_url = $product->image ? url('storage/' . $product->image) : null;

            // Formatter les attributs et leurs options
            $product->attributs = $product->sousCat
                ? $product->sousCat->attributes->map(function ($attribute) {
                    return [
                        'id'      => $attribute->id,
                        'name'    => $attribute->name,
                        'type'    => $attribute->type,
                        'options' => $attribute->options->map(fn($opt) => [
                            'id'    => $opt->id,
                            'value' => $opt->value,
                        ]),
                    ];
                })
                : [];

            return $product;
        });

        return response()->json([
            'success'    => true,
            'products'   => $products->items(),
            'pagination' => [
                'total'        => $products->total(),
                'per_page'     => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
            ],
        ], 200);
    }
    public function show(Request $request, $subCategoryId)
    {
        // Récupérer le produit spécifique en fonction de son ID
        $product = Product::with(['vendor', 'sousCat'])->find($subCategoryId);

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


    // public function getProducts($vendorId)
    // {
    //     try {
    //         $vendor = Vendor::with('products.images')->find($vendorId);

    //         if (!$vendor) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Vendeur non trouvé.',
    //             ], 404);
    //         }

    //         $products = $vendor->products;

    //         if ($products->isEmpty()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Aucun produit trouvé pour ce vendeur.',
    //                 'products' => []
    //             ], 200);
    //         }

    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'Produits récupérés avec succès.',
    //                 'products' => ProductResource::collection($products)
    //             ], 200);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Une erreur s\'est produite : ' . $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function getProducts($vendorId)
    {
        try {
            $vendor = Vendor::with('products.images')->find($vendorId);

            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendeur non trouvé.',
                ], 404);
            }
            $products = $vendor->products;

            if ($products->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Aucun produit trouvé pour ce vendeur.',
                    'products' => [],
                    'stats' => [
                        'total_products'   => 0,
                        'total_stock'      => 0,
                        'active_products'  => 0,
                        'catalogue_value'  => 0,
                    ]
                ], 200);
            }

            // ── Calcul des stats ──────────────────────────────────────────
            $stats = [
                'total_products'  => $products->count(),
                'total_stock'     => $products->sum('stock_quantity'),
                'active_products' => $products->where('is_active', 1)->count(),
                'catalogue_value' => $products->sum(fn($p) => $p->price * $p->stock_quantity),
            ];

            return response()->json([
                'success'  => true,
                'message'  => 'Produits récupérés avec succès.',
                'products' => $products,
                'stats'    => $stats,
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
            'sous_cat_id' => 'required|exists:sous_cats,id', // ← doit accepter la valeur
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        $vendor = auth()->user()->vendor;
        // Sauvegarder l'image
        // $imagePath = $request->file('image')->store('products', 'public');
        DB::beginTransaction();
        // Créer le produit
        try {
            $is_active = 1;
            $product = Product::create([
                'vendor_id' => $vendor->id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'is_active' => $is_active,
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
            return response()->json([
                'message' => 'Erreur: ' . $e->getMessage(),
                'line' => $e->getLine(),        // ← ajouter
                'file' => $e->getFile(),        // ← ajouter
            ], 500);
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


    // public function getPopularProduct()
    // {
    //     // Étape 1 : Récupérer les IDs des 5 produits les plus commandés
    //     $popularProductIds = OrderItem::select('product_id')
    //         ->selectRaw('SUM(quantity) as total_quantity')
    //         ->groupBy('product_id')
    //         ->orderByDesc('total_quantity')
    //         ->limit(5)
    //         ->pluck('product_id');

    //     // Étape 2 : Récupérer les OrderItems associés (1 par produit, sans groupBy SQL)
    //     $popularProducts = OrderItem::with('product')
    //         ->whereIn('product_id', $popularProductIds)
    //         ->get()
    //         ->unique('product_id') // filtre côté Laravel
    //         ->values(); // réindexe proprement

    //     return response()->json([
    //         'popularProducts' => $popularProducts
    //     ], 200);
    // }
    public function getNewProduct()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $productsThisWeek = Product::with([
            'vendor',
            'sousCat',
            'sousCat.attributes.options', // ✅ Ajout
        ])
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($productsThisWeek->isEmpty()) {
            $productsThisWeek = Product::with([
                'vendor',
                'sousCat',
                'sousCat.attributes.options', // ✅ Ajout
            ])
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();
        }

        // ✅ Formatter les attributs
        $productsThisWeek->transform(function ($product) {
            $product->image_url = $product->image
                ? url('storage/' . $product->image)
                : null;

            $product->attributs = $product->sousCat
                ? $product->sousCat->attributes->map(fn($a) => [
                    'id'      => $a->id,
                    'name'    => $a->name,
                    'type'    => $a->type,
                    'options' => $a->options->map(fn($o) => [
                        'id'    => $o->id,
                        'value' => $o->value,
                    ]),
                ])
                : [];

            $product->sousCat?->makeHidden('attributes');

            return $product;
        });

        return response()->json(['newProducts' => $productsThisWeek], 200);
    }

    public function getPopularProduct()
    {
        $popularProductIds = OrderItem::select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->pluck('product_id');

        $popularProducts = Product::with([
            'vendor',
            'images',
            'sousCat',
            'sousCat.attributes.options', // ✅ Ajout
        ])
            ->whereIn('id', $popularProductIds)
            ->where('is_active', 1)
            ->where('is_visible', 1)
            ->get();

        // ✅ Formatter les attributs
        $popularProducts->transform(function ($product) {
            $product->image_url = $product->image
                ? url('storage/' . $product->image)
                : null;

            $product->attributs = $product->sousCat
                ? $product->sousCat->attributes->map(fn($a) => [
                    'id'      => $a->id,
                    'name'    => $a->name,
                    'type'    => $a->type,
                    'options' => $a->options->map(fn($o) => [
                        'id'    => $o->id,
                        'value' => $o->value,
                    ]),
                ])
                : [];

            $product->sousCat?->makeHidden('attributes');

            return $product;
        });

        return response()->json(['popularProducts' => $popularProducts], 200);
    }
    public function getHistory($userId)
    {
        // Récupérer les commandes de l'utilisateur avec les relations nécessaires
        $orders = Order::where('user_id', $userId)
            ->with(['items.product.sousCat']) // Charger les relations
            ->select('id', 'status', 'created_at')
            ->get()
            ->map(function ($order) {
                return [
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'created_at' => $order->created_at->toDateTimeString(),
                    'products' => $order->items->map(function ($item) {
                        return [
                            'product_id'   => $item->product->id,
                            'name' => $item->product->name,
                            'price'        => $item->product->price,
                            'image'        => $item->product->image, // ou ->image_url si tu as un accessor
                            'sousCat'     => $item->product->sousCat ? $item->product->sousCat->name : null,
                            'stock_quantity'     => $item->quantity,
                        ];
                    })->toArray(),
                ];
            });

        return response()->json([
            'orders' => $orders
        ], 200);
    }


    //   public function getNewProduct()
    // {
    //     // Début et fin de la semaine
    //     $startOfWeek = Carbon::now()->startOfWeek();
    //     $endOfWeek = Carbon::now()->endOfWeek();

    //     // Produits ajoutés cette semaine
    //     $productsThisWeek = Product::with('vendor')->whereBetween('created_at', [$startOfWeek, $endOfWeek])
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     // Si aucun produit trouvé, retourner les 5 premiers
    //     if ($productsThisWeek->isEmpty()) {
    //         $productsThisWeek = Product::with('vendor')->orderBy('created_at', 'desc')
    //             ->take(4)
    //             ->get();
    //     }

    //     return response()->json([
    //         'newProducts' => $productsThisWeek
    //     ], 200);
    // }


    public function getPopularCategory()
    {
        $popularCategories = SousCat::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'popularCategories' => $popularCategories
        ], 200);
    }

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
