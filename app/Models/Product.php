<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'price', 
        'original_price',
        'image', 
        'stock'
    ];

    public function categories() { return $this->belongsToMany(Category::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function getRouteKeyName() { return 'slug'; }

}
