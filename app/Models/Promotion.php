<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    /** @use HasFactory<\Database\Factories\PromotionFactory> */
    use HasFactory;
    protected $fillable = [
        'title',
        'discount_percentage',
        'start_date',
        'end_date',
        'sous_cat_id',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];
    public function sousCat()
    {
        return $this->belongsTo(SousCat::class);
    }
}
