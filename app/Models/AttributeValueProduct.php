<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValueProduct extends Model
{
    protected $fillable = ['product_id', 'attribute_option_id', 'additional_price', 'stock_quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

  public function attributeOption()
{
    return $this->belongsTo(AttributeOptions::class, 'attribute_option_id');
}
}
