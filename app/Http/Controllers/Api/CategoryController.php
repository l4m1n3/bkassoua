<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category; // Importation du modèle Category
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories()
    {
        // Récupération de toutes les catégories
        $categories = Category::all();

        // Retour de la réponse avec les catégories
        return response()->json([
            'categories' => $categories,
        ], 200);
    }
}
