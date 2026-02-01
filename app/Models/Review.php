<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'requisition_id',
        'rating',
        'comment',
        'status',
        'rejection_reason',
    ];

    const STATUS_SUSPENDED = 'suspended';
    const STATUS_ACTIVE = 'active';
    const STATUS_REJECTED = 'rejected';

    /**
     * Scope a query to only include active reviews.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include suspended reviews.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', self::STATUS_SUSPENDED);
    }

    /**
     * Scope a query to only include rejected reviews.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }
}
