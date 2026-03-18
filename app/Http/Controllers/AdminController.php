<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\Ad;
use App\Models\SousCat;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use function Symfony\Component\String\b;

class AdminController extends Controller
{
    public function dashboard()
    {
        $customersCount = User::where('role', 'customer')->count();
        $vendorsCount = User::where('role', 'vendor')->count();
        $productsCount = Product::count();
        $ordersCount = Order::count();
        $categoriesCount = Category::count();
        $categories = Category::all();
        $recentOrders = Order::all();

        return view('admin.dashboard', compact(
            'vendorsCount',
            'productsCount',
            'ordersCount',
            'categoriesCount',
            'customersCount',
            'categories',
            'recentOrders'
        ));
    }

    public function changeVendorStatus($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        // Alterne le statut du vendeur
        if ($vendor->status === 'active') {
            $vendor->status = 'inactive';
            $statusMessage = 'suspendu';
        } else {
            $vendor->status = 'active';
            $statusMessage = 'activé';
        }

        $vendor->save();

        return redirect()->route('admin.users')->with('success', "Vendeur {$statusMessage} avec succès.");
    }

    public function users()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $vendors = Vendor::with('user', 'products')
            ->paginate(10);
        $users = User::paginate(10);
        $totalVendors = Vendor::count();
        $pendingUsers = User::where('status', 'pending')->count();
        // $users = User::all();
        // dd($users);
        return view('admin.users', compact('users', 'vendors', 'totalUsers', 'activeUsers', 'totalVendors', 'pendingUsers'));
    }

    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function categories()
    {
        $totalProducts = Product::with('category')->count();
        $categories = Category::with('products')->get();
        return view('admin.category', compact('categories', 'totalProducts'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            // Générer le slug à partir du nom
            $slug = Str::slug($request->name);
            // dd($slug);
            // Gestion de l'image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
            }

            // Création de la catégorie
            Category::create([
                'name' => $request->name,
                'slug' => $slug,
                'image' => $imagePath,
            ]);
            // Redirection avec un message de succès
            return redirect()->route('admin.categories')->with('success', 'La catégorie a été ajoutée avec succès.');
        } catch (Exception $e) {
            // Redirection avec un message d'erreur
            // dd($e);
            return redirect()->route('admin.categories.create')->with('error', 'Une erreur s\'est produite lors de l\'ajout de la catégorie.');
        }
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        try {
            // Validation des données
            $validated =  $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);

            $category = Category::findOrFail($id);

            // Générer le slug à partir du nom
            $slug = Str::slug($request->name);

            // Gestion de l'image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($category->image) {
                    Storage::disk('public')->delete($category->image);
                }
                $imagePath = $request->file('image')->store('categories', 'public');
                $category->image = $imagePath;
            }
            // $category->update($validated);
            // Mise à jour de la catégorie
            $category->name = $request->name;
            $category->slug = $slug;
            $category->update();

            // Redirection avec un message de succès
            return redirect()->route('admin.categories')->with('success', 'La catégorie a été mise à jour avec succès.');
        } catch (Exception $e) {
            // Redirection avec un message d'erreur
            return redirect()->route('admin.categories.edit', $id)->with('error', 'Une erreur s\'est produite lors de la mise à jour de la catégorie.');
        }
    }
    public function destroyCategories($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('admin.categories')->with('success', 'Produit supprimé avec succès');
    }
    // Afficher toutes les commandes pour l'admin


    public function orders()
    {
        $orders = Order::with(['items', 'payment'])
            ->latest()
            ->paginate(10);

        return view('admin.commande', compact('orders'));
    }

    public function cancel(Order $order)
    {
        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cette commande ne peut pas être annulée.'
            ], 422);
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'cancelled']);

            if ($order->payment) {
                $order->payment->update(['status' => 'refunded']);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Commande annulée avec succès.'
        ]);
    }

    public function validatePayment(Order $order)
    {
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Paiement déjà traité.'
            ], 422);
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'processing']);

            if ($order->payment) {
                $order->payment->update(['status' => 'paid']);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Paiement validé avec succès.'
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,shipped,delivered,cancelled'
        ]);

        // Sécurité logique
        if ($order->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Commande annulée, action impossible.'
            ], 422);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Statut de la commande mis à jour.'
        ]);
    }


    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|in:cancel,deliver'
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->ids as $id) {
                $order = Order::find($id);
                if (!$order) continue;

                if ($request->action === 'cancel') {
                    if (in_array($order->status, ['pending', 'processing'])) {
                        $order->update(['status' => 'cancelled']);
                    }
                }

                if ($request->action === 'deliver') {
                    if ($order->status === 'processing') {
                        $order->update(['status' => 'delivered']);
                    }
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Action groupée appliquée avec succès.'
        ]);
    }


    /**
     * Annule une commande
     */


    // Ajouter une nouvelle publicité via un formulaire
    public function storeAd(Request $request)
    {
        // Validation des données du formulaire
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Fichier image
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Gestion de l'upload de l'image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('ads', $imageName, 'public'); // Stockage dans storage/app/public/ads
                $imageUrl = Storage::url($imagePath); // URL publique
            } else {
                throw new \Exception('Aucune image fournie');
            }

            // Création de la publicité
            $ad = Ad::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'image_url' => $imageUrl,
            ]);

            return back()->with('success', 'Publicité ajoutée avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout de la publicité : ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'ajout de la publicité : ' . $e->getMessage());
        }
    }
    public function ads()
    {
        $ads = Ad::all();
        return view('admin.ads', compact('ads'));
    }
    public function destroyAd($id)
    {
        $ad = Ad::findOrFail($id);
        // Supprimer l'image associée
        if ($ad->image_url) {
            $imagePath = str_replace('/storage/', '', $ad->image_url); // Convertir l'URL en chemin de stockage
            Storage::disk('public')->delete($imagePath);
        }
        $ad->delete();
        return redirect()->back()->with('success', 'Annonce supprimée avec succès');
    }
    public function updateAd(Request $request, $id)
    {
        $ad = Ad::findOrFail($id);

        // Validation des données du formulaire
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Fichier image
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Gestion de l'upload de l'image
            if ($request->hasFile('image')) {
                // Supprimer l'ancienne image si elle existe
                if ($ad->image_url) {
                    $oldImagePath = str_replace('/storage/', '', $ad->image_url);
                    Storage::disk('public')->delete($oldImagePath);
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('ads', $imageName, 'public');
                $ad->image_url = Storage::url($imagePath);
            }

            // Mise à jour des autres champs
            $ad->title = $request->input('title');
            $ad->description = $request->input('description');
            $ad->is_active = $request->input('is_active');
            $ad->save();

            return back()->with('success', 'Publicité mise à jour avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la publicité : ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour de la publicité : ' . $e->getMessage());
        }
    }

    public function showSubCategory()
    {
        $Souscategories = SousCat::with('category')->get();
        // dd($Souscategories);
        $totalSubCategories = SousCat::count();
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $categories = Category::all();
        // $activeCategories = Category::where('status', 'active')->count();
        $recentCategories = Category::where('created_at', '>=', now()->subMonth())->count();

        return view('admin.sous_categorie', compact(
            'Souscategories',
            'totalCategories',
            'totalProducts',
            'recentCategories',
            'categories'
        ));
    }
    public function storeSubCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sous_cats,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        try {
            // Générer le slug à partir du nom
            $slug = Str::slug($request->name);
            // Gestion de l'image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('sous_categories', 'public');
            }
            SousCat::create([
                'name' => $request->name,
                'slug' => $slug,
                'image' => $imagePath,
                'category_id' => $request->category_id,
            ]);

            return redirect()->route('admin.categories.showSubCategory')->with('success', 'Sous-catégorie ajoutée avec succès.');
        } catch (Exception $e) {
            //  Log::error('Erreur lors de la commande : ' . $e->getMessage());
            Log::error('Erreur lors de l\'ajout de la sous-catégorie : ' . $e->getMessage());
            return redirect()->route('admin.categories.showSubCategory')->with('error', 'Une erreur s\'est produite lors de l\'ajout de la sous-catégorie.');
        }
    }
    public function showAttributes()
    {
        $attributes = Attribute::with('options', 'sousCategorie')->get();
        $sousCategories = SousCat::all();
        return view('admin.attribut', compact('attributes', 'sousCategories'));
    }
    public function storeAttribute(Request $request)
    {
        try {
            $request->validate([
                'name'               => 'required|string|max:255|unique:attributes,name',
                'sous_cat_id' => 'required|exists:sous_cats,id',
                // 'type'              => 'required|in:texte,couleur,nombre,booleen',
                'value'           => 'nullable|string',

            ]);

            $attribute = Attribute::create([
                'name'            => $request->name,
                'sous_cat_id' => $request->sous_cat_id,
                // 'type'            => $request->type,
            ]);

            // Créer les options si des valeurs sont fournies
            if ($request->filled('value')) {
                $valeurs = array_map('trim', explode(',', $request->value));
                foreach ($valeurs as $valeur) {
                    if (!empty($valeur)) {
                        $attribute->options()->create(['value' => $valeur]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Attribut créé avec succès.');
        } catch (\Throwable $th) {
            Log::error('Erreur lors de la création de l\'attribut : ' . $th->getMessage());
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la création de l\'attribut : ' . $th->getMessage());
        }
    }

    public function updateAttribute(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);

        $request->validate([
            'nom'               => 'required|string|max:255|unique:attributes,name,' . $id,
            'sous_categorie_id' => 'required|exists:sub_categories,id',
            'type'              => 'required|in:texte,couleur,nombre,booleen',
            'statut'            => 'required|in:actif,inactif',
            'valeurs'           => 'nullable|string',
            'description'       => 'nullable|string',
        ]);

        $attribute->update([
            'name'            => $request->nom,
            'sub_category_id' => $request->sous_categorie_id,
            'type'            => $request->type,
            'statut'          => $request->statut,
            'description'     => $request->description,
        ]);

        // Resynchroniser les options
        if ($request->filled('valeurs')) {
            $attribute->options()->delete();
            $valeurs = array_map('trim', explode(',', $request->valeurs));
            foreach ($valeurs as $valeur) {
                if (!empty($valeur)) {
                    $attribute->options()->create(['value' => $valeur]);
                }
            }
        }

        return redirect()->back()->with('success', 'Attribut mis à jour avec succès.');
    }
}
