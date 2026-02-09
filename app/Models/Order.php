<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'order_number',
        'user_id',
        'cart_id',
        'subtotal',
        'tax',
        'tax_rate',
        'shipping',
        'total',
        'currency',
        'status',
        'payment_intent_id',
        'paid_at',
        'delivery_address',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'delivery_address' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate a unique order number
     */
    public static function generateOrderNumber()
    {
        $year = date('Y');
        $lastOrder = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $nextNumber = $lastOrder ? intval(substr($lastOrder->order_number, -6)) + 1 : 1;
        return $year . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if order is paid
     */
    public function isPaid()
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Check if order is pending
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Mark order as paid
     */
    public function markAsPaid($paymentIntentId = null)
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_at' => now(),
            'payment_intent_id' => $paymentIntentId,
        ]);
    }

    /**
     * Mark order as failed
     */
    public function markAsFailed()
    {
        $this->update(['status' => self::STATUS_FAILED]);
    }

    /**
     * Scope for paid orders
     */
    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
