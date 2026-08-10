<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageDeliveryStatusHistory extends Model
{
    protected $fillable = [
        'package_delivery_id',
        'status',
        'note',
    ];

    public function packageDelivery()
    {
        return $this->belongsTo(PackageDelivery::class);
    }
}
