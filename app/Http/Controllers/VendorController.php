<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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


        ]);
        // Vérifier si l'utilisateur existe déjà dans la table vendors
        $existingVendor = Vendor::where('user_id', $request->user_id)->first();
        // $user = User::where('id',Auth::user()->id)->set('role','vendor')->update;

        if ($existingVendor) {
            return response()->json(['error' => 'Cet utilisateur a déjà un vendeur enregistré.'], 400);
        }

        $vendor = new Vendor();
        $vendor->user_id = Auth::user()->id;
        $vendor->store_name = $request->store_name;
        $vendor->address = $request->address;
        $vendor->store_description = $request->store_description;


        if ($request->hasFile('logo')) {
            $vendor->logo = $request->file('logo')->store('vendor_logos', 'public');
        }

        $vendor->save();

        $user = User::where('id',Auth::user()->id)->update(['role'=>'vendor']);
        // dd($user);

        return redirect()->back()->with('message','Votre demande a été envoyee avec succes!');
    }

    // Tableau de bord des vendeurs
    public function dashboard()
    {
        // Récupérer toutes les catégories
        $categories = Category::all();

        // Récupérer le vendeur connecté
        $vendor = Auth::user()->vendor;

        // Récupérer tous les produits du vendeur avec les catégories associées
        $products = $vendor->products()->with('category')->paginate(8); // Vous pouvez aussi paginer les produits

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

    public function storeProduct(Request $request)
    {
        // Étape 1 : Valider les données entrantes
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'price' => 'required|numeric',
                'stock_quantity' => 'required|integer',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'is_active' => 'required|boolean',
                'category_id' => 'required|integer',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Affiche les erreurs de validation
            dd($e->errors());
        }

        // Étape 2 : Vérifier si l'utilisateur est authentifié et s'il a un vendeur
        if (!Auth::check()) {
            return back()->withErrors(['error' => 'Utilisateur non authentifié']);
        }

        if (!Auth::user()->vendor) {
            return back()->withErrors(['error' => 'Aucun vendeur associé à cet utilisateur']);
        }

        // Étape 3 : Créer un produit
        try {
            $product = new Product();
            $product->vendor_id = Auth::user()->vendor->id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stock_quantity = $request->stock_quantity;
            $product->is_active = $request->is_active;
            $product->category_id = $request->category_id;

            // Étape 4 : Gérer l'image du produit
            if ($request->hasFile('image')) {
                $product->image = $request->file('image')->store('product_images', 'public');
            }

            // Sauvegarder le produit
            $product->save();
        } catch (\Exception $e) {
            // Afficher les erreurs de sauvegarde
            return back()->withErrors(['error' => 'Erreur lors de la sauvegarde du produit : ' . $e->getMessage()]);
        }

        // Étape 5 : Rediriger vers le tableau de bord avec un message de succès
        return redirect()->route('vendor.dashboard')->with('success', 'Produit créé avec succès');
    }

    public function updateProduct(Request $request, $id)
    {
        // Vérifier si le produit appartient au vendeur connecté
        $vendor = Auth::user()->vendor;
        $product = $vendor->products()->find($id);

        if (!$product) {
            return redirect()->route('vendor.dashboard')->with('error', 'Produit introuvable ou vous n\'avez pas l\'autorisation de le modifier.');
        }

        // Validation des champs
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'required|boolean',
            'category_id' => 'required|numeric',
        ]);

        // Mise à jour des champs du produit
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock_quantity = $request->stock_quantity;
        $product->is_active = $request->is_active;
        $product->category_id = $request->category_id;

        // Gestion de l'image (si une nouvelle image est téléchargée)
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image && \Storage::exists('public/' . $product->image)) {
                \Storage::delete('public/' . $product->image);
            }

            // Enregistrer la nouvelle image
            $product->image = $request->file('image')->store('product_images', 'public');
        }

        // Sauvegarde des modifications
        $product->save();

        // Redirection avec un message de succès
        return redirect()->route('vendor.dashboard')->with('success', 'Le produit a été mis à jour avec succès.');
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
