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
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
