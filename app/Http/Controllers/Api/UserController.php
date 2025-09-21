<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Vendor;

class UserController extends Controller
{
    public function getUserProfile(Request $request)
    {
        // Vérifie que l'utilisateur est authentifié
        $user = Auth::user();

        // dd($query);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié.'
            ], 401);
        }
        $query = User::with('vendor')->find($user->id);

        // Retourne les informations de l'utilisateur, y compris son rôle
        return response()->json([
            'success' => true,
            'data' => $query
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $user->update($validated);

        return response()->json(['user' => $user], 200);
    }
}
