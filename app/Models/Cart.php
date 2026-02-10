<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'last_activity_at',
        'notified_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the subtotal (sum of all items)
     */
    public function getSubtotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    /**
     * Get total number of items in cart
     */
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty()
    {
        return $this->items->count() === 0;
    }

    /**
     * Update last activity timestamp
     */
    public function touch($attribute = null)
    {
        $this->last_activity_at = now();
        return parent::touch($attribute);
    }
}
