<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
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
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'requested_at',
        'expected_end_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
