<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\SousCat;
use App\Models\AttributeValueProduct;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function showForm()
    {
        $categories = SousCat::all();

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

        $user = User::where('id',$request->user_id)->update(['role'=>'vendor']);
        // dd($user);

        return redirect()->back()->with('message','Votre demande a été envoyee avec succes!');
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

 
//   public function storeProduct(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'description' => 'required|string|max:255',
//             'price' => 'required|numeric|min:0',
//             'stock_quantity' => 'required|integer|min:0',
//             'is_active' => 'required|boolean',
//             'sous_cat_id' => 'required|exists:sous_cats,id',
//             'images' => 'nullable|array|max:4',
//             'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
//             'vendor_id' => 'required|exists:vendors,id',
//         ]);

//         // $vendor = auth()->user()->vendor;
//         $vendor = Vendor::findOrFail($request->vendor_id);
//         DB::beginTransaction();

//         try {
//             $product = Product::create([
//                 // 'vendor_id' => $request->vendor_id,
//                 'name' => $request->name,
//                 'description' => $request->description,
//                 'price' => $request->price,
//                 'stock_quantity' => $request->stock_quantity,
//                 'is_active' => $request->is_active,
//               'sous_cat_id' => $request->sous_cat_id,
//                 'vendor_id'=>$vendor->id
//             ]);

//              if ($request->hasFile('images')) {

//             // Supprimer les anciennes images si elles existent (sécurité)
//             foreach ($product->images as $oldImage) {
//                 Storage::disk('public')->delete($oldImage->path);
//                 $oldImage->delete();
//             }

//             foreach ($request->file('images') as $index => $image) {
//                 $path = $image->store('products', 'public');

//                 // Assurer qu'il n'y a qu'une seule image principale
//                 $product->images()->create([
//                     'path' => $path,
//                     'is_main' => $index === 0,
//                 ]);
//             }
//         }

//             DB::commit();

//             return redirect()
//                 ->back()
//                 ->with('success', 'Produit créé avec succès');
//         } catch (\Throwable $e) {
//             DB::rollBack();
//              Log::error('Erreur lors ajout du produit : ' . $th->getMessage());
//             return back()->withErrors([
//                 'error' => $e->getMessage()
//             ]);
//         }
//     }
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
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:4096',

            // ✅ attributs multiples
            'attribute_option_ids' => 'nullable|array',
            'attribute_option_ids.*' => 'exists:attribute_options,id',
        ]);

        DB::beginTransaction();

        try {

            // ✅ CREATE PRODUIT
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

            // ✅ ATTRIBUTS MULTIPLES
            if ($request->has('attribute_option_ids')) {

                $data = [];

                foreach (array_unique($request->attribute_option_ids) as $optionId) {

                    $data[] = [
                        'product_id' => $product->id,
                        'attribute_option_id' => $optionId,
                        'additional_price' => 0,
                        'stock_quantity' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // insertion optimisée
                AttributeValueProduct::insert($data);
            }

            DB::commit();

            return back()->with('success', 'Produit créé avec succès');
        } catch (\Throwable $e) {
            Log::error('Erreur lors de la création du produit : ' . $e->getMessage());
            DB::rollBack();
             return back()->with('error', 'Produit non créé '.$e->getMessage());
        
        }
    }
    public function updateProduct(Request $request, $id)
    {
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
            'vendor_id' => 'nullable|exists:vendors,id',

            // images
            'images' => 'nullable|array|max:4',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',

            // ✅ attributs multiples
            'attribute_option_ids' => 'nullable|array',
            'attribute_option_ids.*' => 'exists:attribute_options,id',
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
                'vendor_id' => $request->vendor_id ?? $product->vendor_id,
            ]);

            // ✅ UPDATE IMAGES (remplacement complet)
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

            // ✅ UPDATE ATTRIBUTS MULTIPLES

            // supprimer anciens
            $product->attributeValues()->delete();

            // ajouter nouveaux
            if ($request->has('attribute_option_ids')) {

                $data = [];

                foreach (array_unique($request->attribute_option_ids) as $optionId) {

                    $data[] = [
                        'product_id' => $product->id,
                        'attribute_option_id' => $optionId,
                        'additional_price' => 0,
                        'stock_quantity' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                AttributeValueProduct::insert($data);
            }

            DB::commit();

            return back()->with('success', 'Produit mis à jour avec succès');
        } catch (\Throwable $e) {
            Log::error('Erreur lors de la mise à jour du produit : ' . $e->getMessage());
            DB::rollBack();
            dd($e->getMessage());
        }
    }


    public function editProduct(Product $product)
    {
        return view('vendeurs.products.edit', compact('product'));
    }

    public function destroyProduct(Product $product)
    {
        DB::beginTransaction();
        try {
            // Supprimer les images du storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
    
            $product->delete();
    
            DB::commit();
    
            return redirect()
                ->back()
                ->with('success', 'Produit supprimé avec succès');
    
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du produit : ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
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
