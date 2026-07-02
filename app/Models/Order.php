<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'total_price',
    'status',
    'phone',       
    'address',     
    'city',        
    'postal_code', 
];

    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }

    public function items() 
    { 
        return $this->hasMany(OrderItem::class); 
    }

    public function getFormattedTotalAttribute()
    {
        return '$' . number_format($this->total_price, 2);
    }
}