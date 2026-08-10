<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use App\Models\DeliveryZonePrice;
use App\Models\PackageDelivery;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
 
class PackageDeliveryController extends Controller
{
    /**
     * Liste des quartiers disponibles (pour remplir les menus déroulants côté app).
     */
    public function zones()
    {
        return response()->json(
            DeliveryZone::where('is_active', true)->orderBy('name')->get()
        );
    }

    /**
     * Liste des livraisons de l'utilisateur connecté.
     */
    public function index(Request $request)
    {
        $deliveries = PackageDelivery::with(['pickupZone', 'dropoffZone'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return response()->json($deliveries);
    }

    /**
     * Estime le prix sans créer la commande (affichage en temps réel côté app,
     * dès que l'utilisateur a choisi les 2 quartiers + la taille du colis).
     */
    public function estimate(Request $request)
    {
        $validated = $request->validate([
            'pickup_zone_id' => 'required|exists:delivery_zones,id',
            'dropoff_zone_id' => 'required|exists:delivery_zones,id',
            'package_size' => 'required|in:small,medium,large',
        ]);

        try {
            $fee = PackageDelivery::calculateFee(
                $validated['pickup_zone_id'],
                $validated['dropoff_zone_id'],
                $validated['package_size']
            );
        } catch (\RuntimeException $e) {
            throw ValidationException::withMessages([
                'dropoff_zone_id' => "Aucun tarif n'est configuré pour ce trajet.",
            ]);
        }

        return response()->json(['delivery_fee' => $fee]);
    }

    /**
     * Crée une commande de livraison de colis. Le prix est déterminé automatiquement
     * selon le trajet (quartier départ -> quartier arrivée) et la taille du colis.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pickup_zone_id' => 'required|exists:delivery_zones,id',
            'pickup_address' => 'required|string|max:255',
            'pickup_contact_name' => 'nullable|string|max:255',
            'pickup_contact_phone' => 'nullable|string|max:20',

            'dropoff_zone_id' => 'required|exists:delivery_zones,id',
            'dropoff_address' => 'required|string|max:255',
            'recipient_name' => 'nullable|string|max:255',
            'recipient_phone' => 'nullable|string|max:20',

            'package_description' => 'nullable|string|max:255',
            'package_size' => 'required|in:small,medium,large',
            'scheduled_at' => 'nullable|date|after_or_equal:now',
        ]);

        // --- Détermination automatique du prix via la grille tarifaire ---
        try {
            $fee = PackageDelivery::calculateFee(
                $validated['pickup_zone_id'],
                $validated['dropoff_zone_id'],
                $validated['package_size']
            );
        } catch (\RuntimeException $e) {
            throw ValidationException::withMessages([
                'dropoff_zone_id' => "Aucun tarif n'est configuré pour ce trajet. Merci de contacter le support.",
            ]);
        }

        $delivery = PackageDelivery::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'delivery_fee' => $fee,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $delivery->statusHistories()->create([
            'status' => 'pending',
            'note' => 'Commande de livraison créée.',
        ]);

        return response()->json([
            'message' => 'Commande de livraison créée avec succès.',
            'data' => $delivery->load(['pickupZone', 'dropoffZone']),
        ], 201);
    }

    /**
     * Détail d'une livraison, avec son historique.
     */
    public function show(PackageDelivery $packageDelivery)
    {
        return response()->json(
            $packageDelivery->load(['pickupZone', 'dropoffZone', 'statusHistories'])
        );
    }

    /**
     * Mise à jour du statut (côté livreur / admin, un seul livreur donc pas d'assignation).
     */
    public function updateStatus(Request $request, PackageDelivery $packageDelivery)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,picked_up,in_transit,delivered,cancelled',
            'note' => 'nullable|string',
        ]);

        $packageDelivery->status = $validated['status'];

        if ($validated['status'] === 'picked_up') {
            $packageDelivery->picked_up_at = now();
        }

        if ($validated['status'] === 'delivered') {
            $packageDelivery->delivered_at = now();
        }

        $packageDelivery->save();

        $packageDelivery->statusHistories()->create([
            'status' => $validated['status'],
            'note' => $validated['note'] ?? null,
        ]);

        return response()->json($packageDelivery);
    }

    /**
     * Annulation par le client.
     */
    public function cancel(Request $request, PackageDelivery $packageDelivery)
    {
        $validated = $request->validate([
            'cancellation_reason' => 'nullable|string|max:255',
        ]);

        $packageDelivery->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'] ?? null,
        ]);

        $packageDelivery->statusHistories()->create([
            'status' => 'cancelled',
            'note' => $validated['cancellation_reason'] ?? 'Annulée par le client.',
        ]);

        return response()->json(['message' => 'Livraison annulée.']);
    }
}
