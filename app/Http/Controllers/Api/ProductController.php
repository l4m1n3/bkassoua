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
use App\Models\Attribute;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
   


 public function index(Request $request)
    {
        $query = Product::with([
            'vendor',
            'sousCat',
            'attributeValues.attributeOption.attribute'
        ]);

        // Filtre par sous-catégorie
        if ($request->has('sous_cat_id')) {
            $query->where('sous_cat_id', $request->query('sous_cat_id'));
        }

        // Recherche
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->paginate(1000);

        // Transformation
        $products->getCollection()->transform(function ($product) {

            // Image URL
            $product->image = $product->image
                ? url('public/storage/' . $product->image)
                : null;

            // 🔥 Attributs dynamiques
            $product->attributs = $product->attributeValues

                // Sécurité pour éviter les null
                ->filter(function ($val) {
                    return $val->attributeOption && $val->attributeOption->attribute;
                })

                ->groupBy(function ($val) {
                    return $val->attributeOption->attribute->name;
                })

                ->map(function ($values, $name) {

                    $first = $values->first();

                    return [
                        'id'   => $first->attributeOption->attribute->id,
                        'name' => $name,
                        'type' => $first->attributeOption->attribute->type,

                        'options' => $values->map(function ($v) {
                            return [
                                'id'         => $v->id,
                                'value'      => $v->attributeOption->value,
                                'price_plus' => $v->additional_price,
                                'stock'      => $v->stock_quantity,
                            ];
                        })->values()
                    ];
                })
                ->values();

            // Nettoyage
            unset($product->attributeValues);

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
   
//   public function addProduct(Request $request)
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'description' => 'required|string',
//         'price' => 'required|numeric',
//         'stock_quantity' => 'required|integer',
//         'sous_cat_id' => 'required|exists:sous_cats,id',
//         'images' => 'nullable|array|max:4',
//         'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',

//         // 👇 important
//         'attribute_option_ids' => 'nullable|array',
//         'attribute_option_ids.*' => 'exists:attribute_options,id',
//     ]);

//     DB::beginTransaction();

//     try {
//         $user = auth()->user();
//         $vendor = $user->vendor;

//         if (!$vendor) {
//             return response()->json([
//                 'message' => 'Vendor introuvable'
//             ], 403);
//         }

//         // ✅ Création produit
//         $product = Product::create([
//             'vendor_id' => $vendor->id,
//             'name' => $request->name,
//             'description' => $request->description,
//             'price' => $request->price,
//             'stock_quantity' => $request->stock_quantity,
//             'is_active' => 1,
//             'sous_cat_id' => $request->sous_cat_id,
//         ]);

//         // ✅ Images
//         if ($request->hasFile('images')) {
//             foreach ($request->file('images') as $index => $image) {
//                 $product->images()->create([
//                     'path' => $image->store('products', 'public'),
//                     'is_main' => $index === 0,
//                 ]);
//             }
//         }

//         // ✅ ATTRIBUTES (NOUVELLE VERSION SIMPLE)
//         if ($request->has('attribute_option_ids')) {

//             foreach ($request->attribute_option_ids as $optionId) {
//                 $product->attributeValues()->create([
//                     'attribute_option_id' => $optionId,
//                     'additional_price' => 0, // valeur par défaut
//                     'stock_quantity' => 0,   // valeur par défaut
//                 ]);
//             }
//         }

//         // ✅ Charger relations
//         $product->load([
//             'images',
//             'attributeValues.attributeOption'
//         ]);

//         DB::commit();

//         return response()->json($product, 201);

//     } catch (\Exception $e) {

//         DB::rollBack();
//  Log::error('Erreur lors de l\'ajout du produit ' . $e->getMessage());
//         return response()->json([
//             'message' => $e->getMessage(),
//             'line' => $e->getLine(),
//             'file' => $e->getFile(),
//         ], 500);
//     }
// }
    
    public function addProduct(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'stock_quantity' => 'required|integer',
        'sous_cat_id' => 'required|exists:sous_cats,id',
        'images' => 'nullable|array|max:4',
        'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        'attribute_option_ids' => 'nullable|array',
        'attribute_option_ids.*' => 'exists:attribute_options,id',
    ]);

    DB::beginTransaction();

    try {
        $user = auth()->user();
        $vendor = $user->vendor;

        if (!$vendor) {
            return response()->json(['message' => 'Vendor introuvable'], 403);
        }

        // ✅ Création produit
        $product = Product::create([
            'vendor_id' => $vendor->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'is_active' => 1,
            'sous_cat_id' => $request->sous_cat_id,
        ]);

        // ✅ Images
        if ($request->hasFile('images')) {

            // Supprimer les anciennes images si elles existent (sécurité)
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->path);
                $oldImage->delete();
            }

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                // Assurer qu'il n'y a qu'une seule image principale
                $product->images()->create([
                    'path' => $path,
                    'is_main' => $index === 0,
                ]);
            }
        }

        // ✅ ATTRIBUTES
        if ($request->has('attribute_option_ids')) {
            foreach ($request->attribute_option_ids as $optionId) {
                $product->attributeValues()->create([
                    'attribute_option_id' => $optionId,
                    'additional_price' => 0,
                    'stock_quantity' => 0,
                ]);
            }
        }

        // ✅ Charger relations
        $product->load(['images', 'attributeValues.attributeOption']);

        DB::commit();

        return response()->json($product, 201);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur lors de l\'ajout du produit : ' . $e->getMessage());
        return response()->json([
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ], 500);
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



//   public function getHistory($userId)
// {
//     // Récupérer les commandes de l'utilisateur avec les relations nécessaires
//     $orders = Order::where('user_id', $userId)
//         ->with(['items.product.sousCat', 'items.product.attributeValues.attributeOption.attribute',
//         'payment',]) // Charger les relations
//         ->select('id', 'status', 'created_at')
//         ->get()
//         ->map(function ($order) {
//             return [
//                 'order_id' => $order->id,
//                 'status' => $order->status,
//                 'created_at' => $order->created_at->toDateTimeString(),
//                 'products' => $order->items->map(function ($item) {
//                     return [
//                         'product_id'   => $item->product->id,
//                         'name' => $item->product->name,
//                         'price'        => $item->product->price,
//                         'image'        => $item->product->image, // ou ->image_url si tu as un accessor
//                         'sousCat'     => $item->product->sousCat ? $item->product->sousCat->name : null,
//                         'stock_quantity'     => $item->quantity,
//                     ];
//                 })->toArray(),
//             ];
//         });

//     return response()->json([
//         'orders' => $orders
//     ], 200);
// }

public function getHistory($userId)
{
    // ✅ Vérifier que l'utilisateur existe
    if (!$userId || $userId == 0) {
        return response()->json(['orders' => []], 200);
    }

    $orders = Order::where('user_id', $userId)
        ->with([
            'items.product.sousCat',
            'items.product.attributeValues.attributeOption.attribute',
            'payment',
        ])
        ->select('id', 'status', 'created_at')
        ->get()
        ->map(function ($order) {
            return [
                'order_id'   => $order->id,
                'status'     => $order->status,
                'created_at' => $order->created_at->toDateTimeString(),
                'products'   => $order->items
                    // ✅ Ignorer les items dont le produit a été supprimé
                    ->filter(fn($item) => $item->product !== null)
                    ->map(function ($item) {
                        return [
                            'product_id'     => $item->product->id,
                            'name'           => $item->product->name,
                            'price'          => $item->product->price,
                            'image'          => $item->product->image,
                            'sousCat'        => optional($item->product->sousCat)->name,
                            'stock_quantity' => $item->quantity,
                        ];
                    })->values()->toArray(),
            ];
        });

    return response()->json(['orders' => $orders], 200);
}
public function getNewProduct()
{
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek   = Carbon::now()->endOfWeek();

    $productsThisWeek = Product::with([
            'vendor',
            'sousCat',
            'attributeValues.attributeOption.attribute',
        ])
        ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->where('is_active', 1)
        ->where('is_visible', 1)
        ->orderBy('created_at', 'desc')
        ->get();

    if ($productsThisWeek->isEmpty()) {
        $productsThisWeek = Product::with([
                'vendor',
                'sousCat',
                'attributeValues.attributeOption.attribute',
            ])
            ->where('is_active', 1)
            ->where('is_visible', 1)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
    }

    $productsThisWeek->transform(function ($product) {
        $product->image_url = $product->image
            ? url('storage/' . $product->image)
            : null;

        $grouped = [];
        foreach ($product->attributeValues ?? [] as $av) {
            $option    = $av->attributeOption;
            $attribute = $option?->attribute;
            if (!$attribute) continue;

            $attrId = $attribute->id;
            if (!isset($grouped[$attrId])) {
                $grouped[$attrId] = [
                    'id'      => $attribute->id,
                    'name'    => $attribute->name,
                    'type'    => $attribute->type ?? 'select',
                    'options' => [],
                ];
            }
            $grouped[$attrId]['options'][] = [
                'id'    => $option->id,
                'value' => $option->value,
            ];
        }
        $product->attributs = array_values($grouped);
        $product->makeHidden('attributeValues');

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

    $query = Product::with([
            'vendor',
            'images',
            'sousCat',
            'attributeValues.attributeOption.attribute',
        ])
        ->where('is_active', 1)
        ->where('is_visible', 1);

    if ($popularProductIds->isNotEmpty()) {
        $query->whereIn('id', $popularProductIds);
    } else {
        // Fallback : aucune commande encore, prendre les 5 derniers
        $query->orderBy('created_at', 'desc')->limit(5);
    }

    $popularProducts = $query->get();

    $popularProducts->transform(function ($product) {
        $product->image_url = $product->image
            ? url('storage/' . $product->image)
            : null;

        $grouped = [];
        foreach ($product->attributeValues ?? [] as $av) {
            $option    = $av->attributeOption;
            $attribute = $option?->attribute;
            if (!$attribute) continue;

            $attrId = $attribute->id;
            if (!isset($grouped[$attrId])) {
                $grouped[$attrId] = [
                    'id'      => $attribute->id,
                    'name'    => $attribute->name,
                    'type'    => $attribute->type ?? 'select',
                    'options' => [],
                ];
            }
            $grouped[$attrId]['options'][] = [
                'id'    => $option->id,
                'value' => $option->value,
            ];
        }
        $product->attributs = array_values($grouped);
        $product->makeHidden('attributeValues');

        return $product;
    });

    return response()->json(['popularProducts' => $popularProducts], 200);
}


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


// app/Http/Controllers/Api/AttributeController.php
public function getAttributs()
{
    $attributes = Attribute::with('options')->get();
    return response()->json(['attributes' => $attributes]);
}

}
