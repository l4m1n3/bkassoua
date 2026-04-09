<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Vendor;

class UserController extends Controller
{
    public function getUserProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié.'
            ], 401);
        }
        $query = User::with('vendor')->find($user->id);

        return response()->json([
            'success' => true,
            'data' => $query
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'address'      => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
        ]);

        $user->update($validated);

        return response()->json(['user' => $user], 200);
    }

    // ✅ Nouvelle méthode
    public function destroy(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié.'
            ], 401);
        }

        // Validation du mot de passe
        $request->validate([
            'password' => 'required|string',
        ]);

        // Vérification du mot de passe
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mot de passe incorrect.'
            ], 422);
        }

        // Suppression du vendor associé si existant
        if ($user->vendor) {
            $user->vendor()->delete();
        }

        // Révocation de tous les tokens Sanctum
        $user->tokens()->delete();

        // Suppression du compte
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Votre compte a été supprimé définitivement.'
        ], 200);
    }
}