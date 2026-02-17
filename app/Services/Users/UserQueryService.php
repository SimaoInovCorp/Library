<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Http\Request;

class UserQueryService
{
    public function listUsers(Request $request): array
    {
        $sort = $this->sanitizeSort($request->input('sort', 'name'));
        $direction = $this->sanitizeDirection($request->input('direction', 'asc'));

        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $query->orderBy($sort, $direction);

        $users = $query->paginate(10)->appends($request->query());

        return [$users, $sort, $direction];
    }

    private function sanitizeSort(?string $sort): string
    {
        return in_array($sort, ['name', 'email', 'created_at'], true) ? $sort : 'name';
    }

    private function sanitizeDirection(?string $direction): string
    {
        return $direction === 'desc' ? 'desc' : 'asc';
    }
}