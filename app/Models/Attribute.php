<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable = ['sous_cat_id', 'name', 'type'];

    public function options()
    {
        return $this->hasMany(AttributeOptions::class);
    }
    public function sousCategorie()
    {
        return $this->belongsTo(SousCat::class, 'sous_cat_id');
    }
}
