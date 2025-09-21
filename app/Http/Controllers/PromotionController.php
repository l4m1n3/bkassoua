<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('category')->get();
        return response()->json($promotions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category_id' => 'required|exists:categories,id',
        ]);

        $promotion = Promotion::create($request->all());
        return response()->json(['message' => 'Promotion créée avec succès', 'promotion' => $promotion]);
    }

    public function show($id)
    {
        $promotion = Promotion::with('category')->findOrFail($id);
        return response()->json($promotion);
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category_id' => 'required|exists:categories,id',
        ]);

        $promotion->update($request->all());
        return response()->json(['message' => 'Promotion mise à jour avec succès', 'promotion' => $promotion]);
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();
        return response()->json(['message' => 'Promotion supprimée avec succès']);
    }
}
