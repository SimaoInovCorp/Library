<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'book_id',
        'quantity',
        'price_at_purchase',
        'book_name',
        'book_isbn',
    ];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the total for this item
     */
    public function getTotalAttribute()
    {
        return $this->price_at_purchase * $this->quantity;
    }
}
