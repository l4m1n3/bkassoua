<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZonePrice extends Model
{
    protected $fillable = [
        'from_zone_id',
        'to_zone_id',
        'package_size',
        'price',
    ];

    public function fromZone()
    {
        return $this->belongsTo(DeliveryZone::class, 'from_zone_id');
    }

    public function toZone()
    {
        return $this->belongsTo(DeliveryZone::class, 'to_zone_id');
    }

    /**
     * Récupère le prix pour un trajet (quartier départ -> quartier arrivée) et une taille de colis donnés.
     * Lance une exception si aucun tarif n'a été configuré pour cette combinaison.
     */
    public static function findPrice(int $fromZoneId, int $toZoneId, string $packageSize): float
    {
        $entry = static::where('from_zone_id', $fromZoneId)
            ->where('to_zone_id', $toZoneId)
            ->where('package_size', $packageSize)
            ->first();

        if (! $entry) {
            throw new \RuntimeException(
                "Aucun tarif configuré pour ce trajet (zone {$fromZoneId} -> zone {$toZoneId}, taille {$packageSize})."
            );
        }

        return (float) $entry->price;
    }
}
