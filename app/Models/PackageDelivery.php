<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageDelivery extends Model
{
    protected $fillable = [
        'user_id',
        'pickup_zone_id',
        'pickup_address',
        'pickup_contact_name',
        'pickup_contact_phone',
        'dropoff_zone_id',
        'dropoff_address',
        'recipient_name',
        'recipient_phone',
        'package_description',
        'package_size',
        'delivery_fee',
        'status',
        'payment_status',
        'payment_method',
        'scheduled_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pickupZone()
    {
        return $this->belongsTo(DeliveryZone::class, 'pickup_zone_id');
    }

    public function dropoffZone()
    {
        return $this->belongsTo(DeliveryZone::class, 'dropoff_zone_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(PackageDeliveryStatusHistory::class);
    }

    /**
     * Calcule automatiquement le prix de la livraison
     * en fonction du quartier de départ, du quartier d'arrivée et de la taille du colis.
     */
    public static function calculateFee(int $pickupZoneId, int $dropoffZoneId, string $packageSize): float
    {
        return DeliveryZonePrice::findPrice($pickupZoneId, $dropoffZoneId, $packageSize);
    }
}
