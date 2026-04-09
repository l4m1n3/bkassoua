<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use App\Models\Attribute;
use App\Models\AttributeOptions;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Models\SousCat;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class VendorController extends Controller
{
    public function showForm()
    {
        $categories = Category::all();

        return view('vendeurs.register', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',


        ]);
        // Vérifier si l'utilisateur existe déjà dans la table vendors
        $existingVendor = Vendor::where('user_id', $request->user_id)->first();
        // $user = User::where('id',Auth::user()->id)->set('role','vendor')->update;

        if ($existingVendor) {
            return response()->json(['error' => 'Cet utilisateur a déjà un vendeur enregistré.'], 400);
        }

        $vendor = new Vendor();
        $vendor->user_id = $request->user_id;
        $vendor->store_name = $request->store_name;
        $vendor->address = $request->address;
        $vendor->store_description = $request->store_description;
        $vendor->status = 'inactive'; // Par défaut, le vendeur n'est pas approuvé



        if ($request->hasFile('logo')) {
            $vendor->logo = $request->file('logo')->store('vendor_logos', 'public');
        }
        $vendor->save();

        $user = User::where('id', Auth::user()->id)->update(['role' => 'vendor']);
        // dd($user);

        return redirect()->back()->with('message', 'Votre demande a été envoyee avec succes!');
    }

    // Tableau de bord des vendeurs
    public function dashboard()
    {
        // Récupérer toutes les catégories
        $categories = SousCat::all();

        // Récupérer le vendeur connecté
        $vendor = Auth::user()->vendor;

        // Récupérer tous les produits du vendeur avec les catégories associées
        $products = $vendor->products()->with('sousCat')->paginate(8); // Vous pouvez aussi paginer les produits

        // // Récupérer les commandes du vendeur
        // $orders = Order::whereHas('product', function ($query) use ($vendor) {
        //     $query->where('vendor_id', $vendor->id);
        // })->paginate(10);
        // dd($products);
        // Passer les données à la vue
        return view('vendeurs.dashboard', compact('vendor', 'products', 'categories'));
    }


    // Gestion des produits (création, modification, suppression)
    public function createProduct()
    {
        return view('vendor.products.create');
    }

    // public function storeProduct(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'price' => 'required',
    //         'stock_quantity' => 'required',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    //         'is_active' => 'required|boolean',
    //         'cotegory_id' => 'required',
    //     ]);
    //     dd($request);
    //     $product = new Product();
    //     $product->vendor_id = Auth::user()->vendor->id;
    //     $product->name = $request->name;
    //     $product->price = $request->price;
    //     $product->stock_quantity = $request->stock_quantity;
    //     $product->is_active = $request->is_active;
    //     $product->category_id = $request->category_id;

    //     if ($request->hasFile('image')) {
    //         $product->image = $request->file('image')->store('product_images', 'public');
    //     }

    //     $product->save();

    //     return redirect()->route('vendor.dashboard');
    // }

    // public function storeProduct(Request $request)
    // {
    //     // Étape 1 : Valider les données entrantes
    //     try {
    //         $request->validate([
    //             'name' => 'required|string|max:255',
    //             'description' => 'required|string|max:255',
    //             'price' => 'required|numeric',
    //             'stock_quantity' => 'required|integer',
    //             'is_active' => 'required|boolean',
    //             'category_id' => 'required|integer',
    //             'images' => 'nullable|array|max:4',
    //             'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
    //         ]);
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         // Affiche les erreurs de validation
    //         dd($e->errors());
    //     }

    //     // Étape 2 : Vérifier si l'utilisateur est authentifié et s'il a un vendeur
    //     if (!Auth::check()) {
    //         return back()->withErrors(['error' => 'Utilisateur non authentifié']);
    //     }

    //     if (!Auth::user()->vendor) {
    //         return back()->withErrors(['error' => 'Aucun vendeur associé à cet utilisateur']);
    //     }

    //     // Étape 3 : Créer un produit
    //     try {
    //         $product = new Product();
    //         $product->vendor_id = Auth::user()->vendor->id;
    //         $product->name = $request->name;
    //         $product->description = $request->description;
    //         $product->price = $request->price;
    //         $product->stock_quantity = $request->stock_quantity;
    //         $product->is_active = $request->is_active;
    //         $product->category_id = $request->category_id;
    //         $product->save();
    //         // Étape 4 : Gérer l'image du produit
    //         // if ($request->hasFile('image')) {
    //         //     $product->image = $request->file('image')->store('product_images', 'public');
    //         // }
    //         // 📸 Upload images (max 4)
    //         if ($request->hasFile('images')) {
    //             foreach ($request->file('images') as $image) {
    //                 $path = $image->store('products', 'public');

    //                 $product->images()->create([
    //                     'path' => $path
    //                 ]);
    //             }
    //         }
    //         // Sauvegarder le produit

    //     } catch (\Exception $e) {
    //         // Afficher les erreurs de sauvegarde
    //         return back()->withErrors(['error' => 'Erreur lors de la sauvegarde du produit : ' . $e->getMessage()]);
    //     }

    //     // Étape 5 : Rediriger vers le tableau de bord avec un message de succès
    //     return redirect()->route('vendor.dashboard')->with('success', 'Produit créé avec succès');
    // }


    // public function storeProduct(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'description' => 'required|string|max:255',
    //         'price' => 'required|numeric|min:0',
    //         'stock_quantity' => 'required|integer|min:0',
    //         'is_active' => 'required|boolean',
    //         'sous_cat_id' => 'required|exists:sous_cats,id',
    //         'images' => 'nullable|array|max:4',
    //         'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
    //         'vendor_id' => 'required|exists:vendors,id',


    //         // attributs
    //         'attribute_option_id' => 'nullable|array',
    //         'attribute_option_id.*' => 'exists:attribute_options,id',
    //     ]);

    //     // dd($request);
    //     // $vendor = auth()->user()->vendor;

    //     DB::beginTransaction();

    //     try {
    //         $product = Product::create([
    //             'vendor_id' => $request->vendor_id,
    //             'name' => $request->name,
    //             'description' => $request->description,
    //             'price' => $request->price,
    //             'stock_quantity' => $request->stock_quantity,
    //             'is_active' => $request->is_active,
    //             'sous_cat_id' => $request->sous_cat_id,

    //         ]);

    //         if ($request->hasFile('images')) {
    //             foreach ($request->file('images') as $index => $image) {
    //                 $product->images()->create([
    //                     'path' => $image->store('products', 'public'),
    //                     'is_main' => $index === 0, // la 1ère image est principale
    //                 ]);
    //             }
    //         }


    //         // ✅ ATTRIBUTES
    //         if ($request->has('attribute_option_id')) {
    //             foreach ($request->attribute_option_ids as $optionId) {
    //                 $product->attributeValues()->create([
    //                     'attribute_option_id' => $optionId,
    //                     'additional_price' => 0,
    //                     'stock_quantity' => 0,
    //                 ]);
    //             }
    //         }

    //         // ✅ Charger relations
    //         $product->load(['images', 'attributeValues.attributeOption']);

    //         DB::commit();


    //         return redirect()
    //             ->back()
    //             ->with('success', 'Produit créé avec succès');
    //     } catch (\Throwable $e) {
    //         Log::error('Erreur lors de la création du produit : ' . $e->getMessage());
    //         DB::rollBack();
    //         return back()->withErrors([
    //             'error' => $e->getMessage()
    //         ]);
    //     }
    // }
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sous_cat_id' => 'required|exists:sous_cats,id',
            'vendor_id' => 'required|exists:vendors,id',

            // images
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',

            // attribut
            'attribute_option_id' => 'nullable|exists:attribute_options,id',
        ]);

        DB::beginTransaction();

        try {

            $product = Product::create([
                'vendor_id' => $request->vendor_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'sous_cat_id' => $request->sous_cat_id,
            ]);

            // ✅ IMAGES
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');

                    $product->images()->create([
                        'path' => $path,
                        'is_main' => $index === 0,
                    ]);
                }
            }

            // ✅ ATTRIBUT (SINGLE)
            if ($request->filled('attribute_option_id')) {
                $product->attributeValues()->create([
                    'attribute_option_id' => $request->attribute_option_id,
                    'additional_price' => 0,
                    'stock_quantity' => 0,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Produit créé avec succès');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
    public function updateProduct(Request $request, $id)
    {
        // $vendor = Auth::user()->vendor;
        $product = Product::with(['images', 'attributeValues'])->find($id);

        if (!$product) {
            return back()->with('error', 'Produit introuvable.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sous_cat_id' => 'required|exists:sous_cats,id',
            'vendor_id'           => 'nullable|exists:vendors,id',   // ← 
            // images multiples
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',

            // attributs
            'attribute_option_id' => 'nullable',
        ]);

        DB::beginTransaction();

        try {

            // ✅ UPDATE PRODUIT
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock_quantity' => $request->stock_quantity,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'sous_cat_id' => $request->sous_cat_id,
                'vendor_id' => $request->vendor_id ?? $product->vendor_id, // ←
            ]);

            // ✅ UPDATE IMAGES (optionnel : reset total)
            if ($request->hasFile('images')) {

                // supprimer anciennes images
                foreach ($product->images as $img) {
                    if (Storage::exists('public/' . $img->path)) {
                        Storage::delete('public/' . $img->path);
                    }
                    $img->delete();
                }

                // ajouter nouvelles
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');

                    $product->images()->create([
                        'path' => $path,
                        'is_main' => $index === 0,
                    ]);
                }
            }

            // ✅ UPDATE ATTRIBUTS
            if ($request->filled('attribute_option_id')) {

                // supprimer anciens
                $product->attributeValues()->delete();

                // ajouter nouveau
                $product->attributeValues()->create([
                    'attribute_option_id' => $request->attribute_option_id,
                    'additional_price' => 0,
                    'stock_quantity' => 0,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Produit mis à jour avec succès');
        } catch (\Throwable $e) {

            DB::rollBack();
            dd($e->getMessage());
        }
    }



    public function editProduct(Product $product)
    {
        return view('vendeurs.products.edit', compact('product'));
    }



    public function destroyProduct($id)
    {
        // Vérifier si le produit appartient au vendeur connecté
        $vendor = Auth::user()->vendor;
        $product = $vendor->products()->find($id);

        if (!$product) {
            return redirect()->route('vendor.dashboard')->with('error', 'Produit introuvable ou vous n\'avez pas l\'autorisation de le supprimer.');
        }

        // Supprimer l'image du produit si elle existe
        if ($product->image && \Storage::exists('public/' . $product->image)) {
            \Storage::delete('public/' . $product->image);
        }

        // Supprimer le produit de la base de données
        $product->delete();

        // Redirection avec un message de succès
        return redirect()->route('vendeurs.dashboard')->with('success', 'Produit supprimé avec succès.');
    }


    // Gestion des commandes du vendeur
    public function orders()
    {
        $vendor = Auth::user()->vendor;
        $orders = Order::whereHas('product', function ($query) use ($vendor) {
            $query->where('vendor_id', $vendor->id);
        })->paginate(8);
        // dd($orders);
        return view('vendeurs.dashboard', compact('orders'));
    }

    public function showPayments()
    {
        $vendor = Auth::user()->vendor;
        $payments = $vendor->payments; // Les paiements du vendeur a inserer aux moyens de paiement

        return view('vendeurs.payments.index', compact('payments'));
    }
}
