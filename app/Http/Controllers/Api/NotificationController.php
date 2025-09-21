<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id(); // Récupérer l'ID de l'utilisateur connecté

        $notifications = Notification::where('user_id', $userId)
            ->where('read', false) // Supposons que 'read_at' est utilisé pour marquer la lecture
            ->get();

        return response()->json(['notifications' => $notifications]);
    }
    // Marquer une notification comme lue
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        if ($notification->read) {
            return response()->json([
                'success' => true,
                'message' => 'Notification déjà marquée comme lue',
            ], 200);
        }

        $notification->update(['read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marquée comme lue',
        ], 200);
    }

    // Supprimer une notification
    public function destroy($id)
    {
        $user = Auth::user();
        $notification = Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification supprimée avec succès',
        ], 200);
    }
}
