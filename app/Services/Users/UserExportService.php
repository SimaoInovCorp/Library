<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserExportService
{
    public function export(Request $request): StreamedResponse
    {
        abort_unless(auth()->user()?->is_admin, 403);

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

        $fileName = 'users-' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Created At']);

            $query->chunk(500, function ($users) use ($handle) {
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->is_admin ? 'Admin' : 'User',
                        optional($user->created_at)?->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
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