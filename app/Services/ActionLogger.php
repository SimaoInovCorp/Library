<?php

namespace App\Services;

use App\Models\LogEntry;
use App\Models\User;
use Illuminate\Http\Request;

class ActionLogger
{
    public function log(string $module, ?int $objectId, string $description, ?Request $request = null, ?User $user = null): LogEntry
    {
        $req = $request ?? request();
        $actor = $user ?? ($req ? $req->user() : null);
        $now = now();

        return LogEntry::create([
            'user_id' => $actor?->id,
            'date' => $now->toDateString(),
            'time' => $now->toTimeString(),
            'module' => $module,
            'object_id' => $objectId,
            'description' => $description,
            'ip_address' => $req?->ip(),
            'user_agent' => $req?->userAgent(),
        ]);
    }
}