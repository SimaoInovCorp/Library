<?php

namespace App\Services\Requisitions;

use App\Models\Book;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RequisitionQueryService
{
    public function getIndexData(User $user, array $queryParams): array
    {
        $search = $queryParams['search'] ?? null;

        $requisitions = $user->requisitions()
            ->with('book')
            ->latest()
            ->paginate(10, ['*'], 'requisitions_page')
            ->appends($queryParams);

        $allRequisitions = null;
        if ($user->is_admin) {
            $allRequisitions = Requisition::with(['book', 'user'])
                ->latest()
                ->paginate(10, ['*'], 'all_requisitions_page')
                ->appends($queryParams);
        }

        $availableBooks = $this->getAvailableBooks($queryParams, 'available_books_page');

        $currentLoans = $user->requisitions()
            ->with('book.authors')
            ->where('status', 'approved')
            ->latest()
            ->take(5)
            ->get();

        $borrowingHistory = $user->requisitions()
            ->with('book.authors')
            ->where('status', 'returned')
            ->latest()
            ->take(5)
            ->get();

        return [
            'requisitions' => $requisitions,
            'availableBooks' => $availableBooks,
            'allRequisitions' => $allRequisitions,
            'activeCount' => Requisition::activeCount(),
            'last30DaysCount' => Requisition::last30DaysCount(),
            'returnedTodayCount' => Requisition::returnedTodayCount(),
            'currentLoans' => $currentLoans,
            'borrowingHistory' => $borrowingHistory,
            'search' => $search,
        ];
    }

    public function getAvailableBooks(array $queryParams, string $pageName = 'page'): LengthAwarePaginator
    {
        $search = $queryParams['search'] ?? null;

        $booksWithPending = Requisition::where('status', 'pending')->pluck('book_id');

        return Book::with(['publisher', 'authors'])
            ->whereNotIn('id', $booksWithPending)
            ->when($search, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('isbn', 'like', "%{$term}%");
                });
            })
            ->orderBy('name')
            ->paginate(10, ['*'], $pageName)
            ->appends($queryParams);
    }
}