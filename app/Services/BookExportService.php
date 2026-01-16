<?php

namespace App\Services;

use App\Models\Book;

class BookExportService
{
    /**
     * Export books as a CSV stream response.
     */
    public function exportCsv()
    {
        $books = Book::with(['publisher', 'authors'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="books.csv"',
        ];

        $callback = function() use ($books) {
            $handle = fopen('php://output', 'w');
            // Header row
            fputcsv($handle, ['ID', 'ISBN', 'Name', 'Publisher', 'Authors', 'Price']);
            foreach ($books as $book) {
                fputcsv($handle, [
                    $book->id,
                    $book->isbn,
                    $book->name,
                    $book->publisher ? $book->publisher->name : '',
                    $book->authors->pluck('name')->join(', '),
                    $book->price,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
