<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category; // Importation du modèle Category
use App\Models\SousCat;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories()
    {
        // Récupération de toutes les catégories avec leurs sous-catégories
        $categories = Category::with('sousCat')->get();
        return response()->json([
            'categories' => $categories,
        ], 200);
    }

    public function showSubCategory($categoryId)
    {
        if (!$categoryId) {
            return response()->json(['error' => 'category_id is required'], 400);
        }

        $sousCategories = SousCat::where('category_id', $categoryId)->get();
        return response()->json([
            'sous_categories' => $sousCategories,
        ], 200);
    }
}
