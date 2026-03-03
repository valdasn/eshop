<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * This allows us to save the order from the CartController.
     */
    protected $fillable = [
    'user_id',
    'total_price',
    'status',
    'phone',       // Add this
    'address',     // Add this
    'city',        // Add this
    'postal_code', // Add this
];

    /* --- Relationships --- */

    public function user() 
    { 
        return $this->belongsTo(User::class); 
    }

    public function items() 
    { 
        return $this->hasMany(OrderItem::class); 
    }

    /**
     * Returns a formatted price for the UI (e.g., $150.00)
     */
    public function getFormattedTotalAttribute()
    {
        return '$' . number_format($this->total_price, 2);
    }
}