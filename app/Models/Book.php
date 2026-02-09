<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = [
        'isbn',
        'name',
        'bibliography',
        'cover_image',
        'price',
        'publisher_id',
        'copies',
    ];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }
    /**
     * Get all requisitions for this book.
     */
    public function requisitions()
    {
        return $this->hasMany(Requisition::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get only active (approved) reviews for this book.
     */
    public function activeReviews()
    {
        return $this->hasMany(Review::class)->where('status', Review::STATUS_ACTIVE);
    }

    /**
     * Get cart items for this book.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get order items for this book.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
