<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'type'];

    public function options()
    {
        return $this->hasMany(AttributeOptions::class);
    }
    public function values()
    {
        return $this->hasMany(AttributeValueProduct::class);
    }
}
