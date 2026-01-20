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
}
