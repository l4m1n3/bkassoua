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
use App\Models\DeliveryRegion;
use App\Models\PackageDelivery;
use App\Models\DeliveryZone;
use App\Models\DeliveryZonePrice;
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
        // Stats globales
        $customersCount  = User::where('role', 'customer')->count();
        $vendorsCount    = Vendor::count();
        $adminsCount     = User::where('role', 'admin')->count();
        $productsCount   = Product::count();
        $categoriesCount = Category::count();
        $sousCategoriesCount = SousCat::count();

        // Commandes
        $ordersCount  = Order::count();
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Chiffre d'affaires
        $revenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');

        // Nouveaux utilisateurs ce mois
        $newUsersCount = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Taux de conversion (commandes / clients * 100)
        $conversionRate = $customersCount > 0
            ? round(($ordersCount / $customersCount) * 100, 1)
            : 0;

        // Note moyenne (si vous avez un modèle Review)
        // $averageRating = Review::avg('rating') ?? 4.5;
        $averageRating = 4.5;

        // Ventes par mois (compatible PostgreSQL et MySQL)
        $salesRaw = Order::whereYear('created_at', now()->year)
            ->where('status', '!=', 'cancelled')
            ->selectRaw('EXTRACT(MONTH FROM created_at)::integer AS month, SUM(total_amount) AS total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $salesData = [];
        foreach ($salesRaw as $row) {
            $salesData[(int) $row->month] = (float) $row->total;
        }

        $salesChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $salesChart[] = $salesData[$i] ?? 0;
        }

        // Produits par catégorie
        $categoryData   = Category::withCount('products')->get();
        $categoryLabels = $categoryData->pluck('name');
        $categoryCounts = $categoryData->pluck('products_count');

        // Catégories pour le filtre
        $categories = Category::orderBy('name')->get();

        return view('admin.dashboard', compact(
            'customersCount',
            'vendorsCount',
            'adminsCount',
            'productsCount',
            'categoriesCount',
            'ordersCount',
            'recentOrders',
            'revenue',
            'newUsersCount',
            'conversionRate',
            'averageRating',
            'salesChart',
            'categoryLabels',
            'categoryCounts',
            'categories',
            'sousCategoriesCount'
        ));
    }

    public function dashboardData()
    {
        return response()->json([
            'customers' => User::where('role', 'customer')->count(),
            'vendors'   => Vendor::count(),
            'products'  => Product::count(),
            'orders'    => Order::count(),
            'revenue'   => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'salesChart' => $this->getSalesChartData(),
        ]);
    }

    private function getSalesChartData(): array
    {
        $salesRaw = Order::whereYear('created_at', now()->year)
            ->where('status', '!=', 'cancelled')
            ->selectRaw('EXTRACT(MONTH FROM created_at)::integer AS month, SUM(total_amount) AS total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $salesData = [];
        foreach ($salesRaw as $row) {
            $salesData[(int) $row->month] = (float) $row->total;
        }

        $chart = [];
        for ($i = 1; $i <= 12; $i++) {
            $chart[] = $salesData[$i] ?? 0;
        }

        return $chart;
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

    // public function orders()
    // {
    //     $orders = Order::with(['items', 'payment','vendor'])
    //         ->latest()
    //         ->paginate(10);

    //     dd($orders);
    //     return view('admin.commande', compact('orders'));
    // }
  public function orders()
{
    $orders = Order::with([
        'user',
        'items.product.images',
        'payment',
    ])
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
        $attributes = Attribute::with('options')->get();
        $sousCategories = SousCat::all();
        return view('admin.attribut', compact('attributes', 'sousCategories'));
    }
    public function storeAttribute(Request $request)
    {
        try {
            $request->validate([
                'name'               => 'required|string|max:255|unique:attributes,name',
                'type'              => 'required|in:texte,couleur,nombre,booleen',
                'value'           => 'nullable|string',

            ]);

            $attribute = Attribute::create([
                'name'            => $request->name,
                'type'            => $request->type,
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

    public function latest(Request $request)
    {
        $lastId = $request->last_id ?? 0;

        $orders = Order::with(['user', 'payment'])
            ->withCount('items')
            ->where('id', '>', $lastId)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'html' => view('admin.order_rows', compact('orders'))->render(),
            'last_id' => $orders->max('id')
        ]);
    }

    public function delivery()
    {
        $deliveryRegions = DeliveryRegion::all();
        return view('admin.delivery', compact('deliveryRegions'));
    }
    public function deliveries()
    {
        $deliveryRegions = DeliveryRegion::all();
        $totalRegions    = $deliveryRegions->count();
        $regionActive    = $deliveryRegions->where('is_active', true)->count();
        $regionInactive  = $deliveryRegions->where('is_active', false)->count();
        $feeMoyen        = $deliveryRegions->avg('fee');

        return view('admin.delivery', compact(
            'deliveryRegions',
            'totalRegions',
            'regionActive',
            'regionInactive',
            'feeMoyen'
        ));
    }

    public function storeDelivery(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:delivery_regions,name',
            'fee'  => 'required|numeric|min:0',
        ]);

        DeliveryRegion::create([
            'name' => $request->name,
            'fee'  => $request->fee,
        ]);

        return redirect()->back()->with('success', 'Région de livraison créée avec succès.');
    }

    public function updateDelivery(Request $request, $id)
    {
        $region = DeliveryRegion::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:delivery_regions,name,' . $id,
            'fee'  => 'required|numeric|min:0',
        ]);

        $region->update([
            'name' => $request->name,
            'fee'  => $request->fee,
        ]);

        return redirect()->back()->with('success', 'Région mise à jour avec succès.');
    }

    public function destroyDelivery($id)
    {
        DeliveryRegion::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Région supprimée avec succès.');
    }
    public function vendorProducts($vendorId)
    {
        $vendor   = Vendor::with(['user', 'products.images', 'products.sousCat'])->findOrFail($vendorId);
        $products = $vendor->products()->with(['images', 'sousCat'])->paginate(10);

        return view('admin.vendor_products', compact('vendor', 'products'));
    }

    // ───────────────────────────────────────────────────────────────────────
    // COMMANDES DE LIVRAISON (package_deliveries)
    // ───────────────────────────────────────────────────────────────────────

    public function packageDeliveries(Request $request)
    {
        $query = PackageDelivery::with(['user', 'pickupZone', 'dropoffZone'])->latest();

        // ── Recherche ────────────────────────────────────────────────────
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('recipient_name', 'LIKE', "%{$search}%")
                    ->orWhere('recipient_phone', 'LIKE', "%{$search}%")
                    ->orWhereHas(
                        'user',
                        fn($u) =>
                        $u->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('phone_number', 'LIKE', "%{$search}%")
                    );
            });
        }

        // ── Statut ───────────────────────────────────────────────────────
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // ── Filtres par quartier ─────────────────────────────────────────
        if ($request->filled('pickup_zone_id')) {
            $query->where('pickup_zone_id', $request->pickup_zone_id);
        }
        if ($request->filled('dropoff_zone_id')) {
            $query->where('dropoff_zone_id', $request->dropoff_zone_id);
        }

        // ── Filtres de date ──────────────────────────────────────────────
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $packageDeliveries = $query->paginate(10)->withQueryString();

        // ── Statistiques ─────────────────────────────────────────────────
        $totalDeliveries      = PackageDelivery::count();
        $pendingDeliveries    = PackageDelivery::where('status', 'pending')->count();
        $ongoingDeliveries    = PackageDelivery::whereIn('status', ['accepted', 'picked_up', 'in_transit'])->count();
        $deliveredDeliveries  = PackageDelivery::where('status', 'delivered')->count();
        $cancelledDeliveries  = PackageDelivery::where('status', 'cancelled')->count();
        $totalDeliveryRevenue = PackageDelivery::where('status', 'delivered')->sum('delivery_fee');

        $zones = DeliveryZone::orderBy('name')->get();
        // ── Tarifs entre les zones ───────────────────────────────────
        $zonePrices = DeliveryZonePrice::with([
            'fromZone',
            'toZone'
        ])->get();
        return view('admin.package_deliveries', compact(
            'packageDeliveries',
            'totalDeliveries',
            'pendingDeliveries',
            'ongoingDeliveries',
            'deliveredDeliveries',
            'cancelledDeliveries',
            'totalDeliveryRevenue',
            'zones',
            'zonePrices'
        ));
    }

    public function showPackageDelivery(PackageDelivery $packageDelivery)
    {
        $packageDelivery->load([
            'user',
            'pickupZone',
            'dropoffZone',
            'statusHistories' => fn($q) => $q->latest(),
        ]);

        return view('admin.package_delivery_show', compact('packageDelivery'));
    }

    public function updatePackageDeliveryStatus(Request $request, PackageDelivery $packageDelivery)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,picked_up,in_transit,delivered,cancelled',
            'note'   => 'nullable|string',
        ]);

        if ($packageDelivery->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Cette livraison est annulée, action impossible.',
            ], 422);
        }

        DB::transaction(function () use ($request, $packageDelivery) {
            $packageDelivery->status = $request->status;

            if ($request->status === 'picked_up') {
                $packageDelivery->picked_up_at = now();
            }
            if ($request->status === 'delivered') {
                $packageDelivery->delivered_at = now();
            }

            $packageDelivery->save();

            $packageDelivery->statusHistories()->create([
                'status' => $request->status,
                'note'   => $request->note ?? 'Mis à jour par un administrateur.',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Statut de la livraison mis à jour.',
        ]);
    }

    public function cancelPackageDelivery(Request $request, PackageDelivery $packageDelivery)
    {
        if (!in_array($packageDelivery->status, ['pending', 'accepted'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cette livraison ne peut pas être annulée.',
            ], 422);
        }

        DB::transaction(function () use ($request, $packageDelivery) {
            $packageDelivery->update([
                'status'               => 'cancelled',
                'cancellation_reason'  => $request->input('reason'),
            ]);

            $packageDelivery->statusHistories()->create([
                'status' => 'cancelled',
                'note'   => $request->input('reason') ?? 'Annulée par un administrateur.',
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Livraison annulée avec succès.',
        ]);
    }

    public function bulkPackageDeliveryAction(Request $request)
    {
        $request->validate([
            'ids'    => 'required|array',
            'action' => 'required|in:cancel,deliver',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->ids as $id) {
                $delivery = PackageDelivery::find($id);
                if (!$delivery) continue;

                if ($request->action === 'cancel' && in_array($delivery->status, ['pending', 'accepted'])) {
                    $delivery->update(['status' => 'cancelled']);
                    $delivery->statusHistories()->create([
                        'status' => 'cancelled',
                        'note'   => 'Annulée par un administrateur (action groupée).',
                    ]);
                }

                if ($request->action === 'deliver' && in_array($delivery->status, ['picked_up', 'in_transit'])) {
                    $delivery->update(['status' => 'delivered', 'delivered_at' => now()]);
                    $delivery->statusHistories()->create([
                        'status' => 'delivered',
                        'note'   => 'Marquée livrée par un administrateur (action groupée).',
                    ]);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Action groupée appliquée avec succès.',
        ]);
    }

    /**
     * Polling temps réel façon `latest()` pour les commandes produits.
     */
    public function latestPackageDeliveries(Request $request)
    {
        $lastId = $request->last_id ?? 0;

        $packageDeliveries = PackageDelivery::with(['user', 'pickupZone', 'dropoffZone'])
            ->where('id', '>', $lastId)
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'html'    => view('admin.package_delivery_rows', compact('packageDeliveries'))->render(),
            'last_id' => $packageDeliveries->max('id'),
        ]);
    }

    // ───────────────────────────────────────────────────────────────────────
    // QUARTIERS & GRILLE TARIFAIRE (delivery_zones / delivery_zone_prices)
    // ───────────────────────────────────────────────────────────────────────

    public function packageDeliverySettings()
    {
        $zones      = DeliveryZone::orderBy('name')->get();
        $zonePrices = DeliveryZonePrice::with(['fromZone', 'toZone'])
            ->orderBy('from_zone_id')
            ->orderBy('to_zone_id')
            ->get();

        $totalZones  = $zones->count();
        $totalPrices = $zonePrices->count();

        return view('admin.package_delivery_settings', compact(
            'zones',
            'zonePrices',
            'totalZones',
            'totalPrices'
        ));
    }

    public function storePackageDeliveryZone(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:delivery_zones,name',
        ]);

        DeliveryZone::create([
            'name'      => $request->name,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Quartier ajouté avec succès.');
    }

    public function updatePackageDeliveryZone(Request $request, $id)
    {
        $zone = DeliveryZone::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255|unique:delivery_zones,name,' . $id,
            'is_active' => 'nullable|boolean',
        ]);

        $zone->update([
            'name'      => $request->name,
            'is_active' => $request->boolean('is_active', $zone->is_active),
        ]);

        return redirect()->back()->with('success', 'Quartier mis à jour avec succès.');
    }

    public function destroyPackageDeliveryZone($id)
    {
        $zone = DeliveryZone::findOrFail($id);

        // Empêche la suppression d'un quartier déjà utilisé dans une livraison
        // ou dans la grille tarifaire, pour ne pas casser l'historique.
        $isUsedInDeliveries = PackageDelivery::where('pickup_zone_id', $id)
            ->orWhere('dropoff_zone_id', $id)
            ->exists();
        $isUsedInPrices = DeliveryZonePrice::where('from_zone_id', $id)
            ->orWhere('to_zone_id', $id)
            ->exists();

        if ($isUsedInDeliveries) {
            return redirect()->back()->with('error', 'Ce quartier est utilisé dans des livraisons existantes et ne peut pas être supprimé.');
        }

        if ($isUsedInPrices) {
            return redirect()->back()->with('error', 'Supprime d\'abord les tarifs liés à ce quartier avant de le supprimer.');
        }

        $zone->delete();

        return redirect()->back()->with('success', 'Quartier supprimé avec succès.');
    }

    public function storePackageDeliveryZonePrice(Request $request)
    {
        $request->validate([
            'from_zone_id'  => 'required|exists:delivery_zones,id',
            'to_zone_id'    => 'required|exists:delivery_zones,id|different:from_zone_id',
            'package_size'  => 'required|in:small,medium,large',
            'price'         => 'required|numeric|min:0',
        ]);

        $exists = DeliveryZonePrice::where('from_zone_id', $request->from_zone_id)
            ->where('to_zone_id', $request->to_zone_id)
            ->where('package_size', $request->package_size)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Un tarif existe déjà pour ce trajet et cette taille de colis.');
        }

        DeliveryZonePrice::create($request->only(['from_zone_id', 'to_zone_id', 'package_size', 'price']));

        return redirect()->back()->with('success', 'Tarif ajouté avec succès.');
    }

    public function updatePackageDeliveryZonePrice(Request $request, $id)
    {
        $zonePrice = DeliveryZonePrice::findOrFail($id);

        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $zonePrice->update(['price' => $request->price]);

        return redirect()->back()->with('success', 'Tarif mis à jour avec succès.');
    }

    public function destroyPackageDeliveryZonePrice($id)
    {
        DeliveryZonePrice::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Tarif supprimé avec succès.');
    }
}
