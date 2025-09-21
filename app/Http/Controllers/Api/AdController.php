<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ad;

class AdController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer uniquement les annonces actives
        $ads = Ad::where('is_active', true)->get();
        return response()->json([
            'success' => true,
            'data' => $ads,
        ], 200);
    }

    // Optionnel : Ajouter une méthode pour une annonce spécifique
    public function show($id)
    {
        $ad = Ad::findOrFail($id);
        if (!$ad->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Annonce non active',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $ad,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
