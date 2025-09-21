<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;
    protected $fillable = ['id', 'name', 'slug', 'image'];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    // Relation many-to-many avec User
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }
}
