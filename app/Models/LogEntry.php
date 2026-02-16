<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{
    use HasFactory;

    protected $table = 'logs';

    protected $fillable = [
        'user_id',
        'date',
        'time',
        'module',
        'object_id',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i:s',
        'object_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}