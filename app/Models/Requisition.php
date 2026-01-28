<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'requested_at',
        'expected_end_at',
        // 'number' is not fillable, it's set automatically
    ];

    protected static function booted()
    {
        static::creating(function ($requisition) {
            // Get the current max number and increment
            $max = self::max('number');
            $requisition->number = $max ? $max + 1 : 1;
        });
    }

    /**
     * Get the due status for the requisition.
     */
    public function getDueStatusAttribute()
    {
        if (!$this->expected_end_at) {
            return 'N/A';
        }
        $now = now();
        $end = \Carbon\Carbon::parse($this->expected_end_at);
        if ($now->isSameDay($end)) {
            return 'Due Today';
        } elseif ($now->greaterThan($end)) {
            return 'Overdue';
        } else {
            return 'On Time';
        }
    }

    /**
     * Get the count of active requisitions (approved and not yet returned).
     */
    public static function activeCount()
    {
        return self::where('status', 'approved')->count();
    }

    /**
     * Get the count of requisitions created in the last 30 days.
     */
    public static function last30DaysCount()
    {
        return self::where('created_at', '>=', now()->subDays(30))->count();
    }

    /**
     * Get the count of books returned today.
     * Assumes status is set to 'returned' and updated_at is the return time.
     */
    public static function returnedTodayCount()
    {
        return self::where('status', 'returned')
            ->whereDate('updated_at', now()->toDateString())
            ->count();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
