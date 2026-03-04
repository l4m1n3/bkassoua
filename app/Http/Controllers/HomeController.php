<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Vendor;
use App\Models\Promotion;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {

        // Obtenir la date de début de la semaine
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Requête pour les produits ajoutés cette semaine
        $productsThisWeeks = Product::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
        $productsCount = Product::count();
        dd($productsThisWeeks);
        // $categories = Category::all();
        $promotions = Promotion::with('category')->get();
        $popularProducts = Product::whereIn('id', OrderItem::pluck('product_id'))
            ->distinct()
            ->get();
        $categories = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->get();
        $vendor = Vendor::where('user_id', Auth::id())->first();
        // dd($popularProducts);
        // return view('layouts.master', compact('categories','vendor'));
        return view('layouts.masters', compact(['categories', 'promotions', 'popularProducts', 'productsThisWeeks', 'vendor', 'productsCount']));
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        $categories = Category::all();
        // Rechercher dans les colonnes name et description du produit
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->paginate(10);

        return view('shop.shop', compact('products', 'query', 'categories'));
    }
    public function home()
    {
        // Obtenir la date de début de la semaine
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Requête pour les produits ajoutés cette semaine
        // $productsThisWeek = Product::with(['images', 'mainImage'])->get();

        $productsThisWeeks = Product::with(['images', 'mainImage'])->whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();
        // dd($productsThisWeeks);
        $productsCount = Product::count();
        $promotions = Promotion::with('category')->get();
        $popularProducts = Product::whereIn('id', OrderItem::pluck('product_id'))
            ->distinct()
            ->get();
        $categories = Category::whereHas('products')
            ->withCount('products')
            ->get();

        $vendor = Vendor::where('user_id', Auth::id())->first();
        return view('layouts.masters', compact(['categories', 'promotions', 'popularProducts', 'productsThisWeeks', 'vendor','productsCount']));
    }
}
