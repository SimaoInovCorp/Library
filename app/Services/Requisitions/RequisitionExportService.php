<?php

namespace App\Services\Requisitions;

use App\Models\Requisition;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RequisitionExportService
{
    public function export(): StreamedResponse
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $query = Requisition::with(['user', 'book'])
            ->orderByDesc('created_at');

        $fileName = 'requisitions-' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
        ];

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Number', 'User', 'Book', 'Status', 'Requested At', 'Expected End At']);

            $query->chunk(500, function ($requisitions) use ($handle) {
                foreach ($requisitions as $req) {
                    fputcsv($handle, [
                        $req->id,
                        $req->number,
                        optional($req->user)->name,
                        optional($req->book)->name,
                        $req->status,
                        optional($req->requested_at)?->toDateTimeString(),
                        optional($req->expected_end_at)?->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }
}