<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category; // Importation du modèle Category
use App\Models\SousCat;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // public function categories()
    // {
    //     // Récupération de toutes les catégories avec leurs sous-catégories
    //     $categories = Category::with('sousCat')->get();
    //     return response()->json([
    //         'categories' => $categories,
    //     ], 200);
    // }

    // public function showSubCategory($categoryId)
    // {
    //     if (!$categoryId) {
    //         return response()->json(['error' => 'category_id is required'], 400);
    //     }

    //     $sousCategories = SousCat::where('category_id', $categoryId)->get();
    //     return response()->json([
    //         'sous_categories' => $sousCategories,
    //     ], 200);
    // }
    public function categories()
    {
        // Récupération des catégories qui ont au moins une sous-catégorie avec des produits
        $categories = Category::with(['sousCat' => function ($query) {
            $query->whereHas('products'); // Seulement les sous-catégories avec des produits
        }])
            ->whereHas('sousCat.products') // Seulement les catégories ayant des sous-cat avec produits
            ->get();

        return response()->json([
            'categories' => $categories,
        ], 200);
    }

    public function showSubCategory($categoryId)
    {
        if (!$categoryId) {
            return response()->json(['error' => 'category_id is required'], 400);
        }

        // Seulement les sous-catégories ayant des produits
        $sousCategories = SousCat::where('category_id', $categoryId)
            ->whereHas('products')
            ->get();

        return response()->json([
            'sous_categories' => $sousCategories,
        ], 200);
    }
}
