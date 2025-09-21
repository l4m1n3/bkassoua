<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    protected $fillable = [
        'vendor_id', 
        'name', 
        'description', 
        'price', 
        'stock_quantity', 
        'image', 
        'is_active', 
        'category_id'];
    
        public function vendor()
        {
            return $this->belongsTo(Vendor::class);
        }
    
        public function orders()
        {
            return $this->belongsToMany(Order::class);
        }
        public function category()
        {
            return $this->belongsTo(Category::class);
        }
        public function order_item()
        {
            return $this->belongsTo(OrderItem::class);
        }
}
