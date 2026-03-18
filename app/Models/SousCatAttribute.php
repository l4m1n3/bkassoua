<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SousCatAttribute extends Model
{
    use HasFactory;
    protected $fillable = ['sous_cat_id', 'attribute_id', 'is_required'];
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
