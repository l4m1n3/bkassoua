<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Http\Requests\StorePromotionRequest;
use App\Http\Requests\UpdatePromotionRequest;
// use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\SousCat;

use function Symfony\Component\String\b;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('sousCat')->get();
        $sousCategories = SousCat::all();
        // dd($promotions);
        return view('admin.promotion', compact('promotions', 'sousCategories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'sous_cat_id' => 'required|exists:sous_cats,id',
            ]);

            Promotion::create($request->all());
            return back()->with('success', 'Promotion créée avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la promotion: ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de la création de la promotion', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $promotion = Promotion::with('sousCat')->findOrFail($id);
        return response()->json($promotion);
    }

    public function update(Request $request, $id)
    {
        try {
            $promotion = Promotion::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'sous_cat_id' => 'required|exists:sous_cats,id',
            ]);

            $promotion->update($request->all());
            return back()->with('success', 'Promotion mise à jour avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la  mise à jour de la promotion: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour de la promotion: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();
        return response()->json(['message' => 'Promotion supprimée avec succès']);
    }
}
