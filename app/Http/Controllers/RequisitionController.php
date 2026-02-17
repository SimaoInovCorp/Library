<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Requisition;
use App\Models\User;
use App\Notifications\RequisitionCreatedNotification;
use App\Services\ActionLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequisitionController extends Controller
{
    private ActionLogger $logger;

    public function __construct(ActionLogger $logger)
    {
        $this->logger = $logger;
    }

    public function index()
    {
        $user = auth()->user();


        // Admins see all requisitions, but also only their own for 'My Book Requests'
        if ($user->is_admin) {
            $allRequisitions = Requisition::with(['book', 'user'])->latest()->paginate(10);
            $requisitions = $user->requisitions()->with('book')->latest()->paginate(10);
        } else {
            $allRequisitions = null;
            $requisitions = $user->requisitions()->with('book')->latest()->paginate(10);
        }

        // Get IDs of books with any pending requisition (from any user)
        $booksWithPending = Requisition::where('status', 'pending')->pluck('book_id');

        // Get all books not in a pending requisition
        $availableBooks = Book::with('publisher', 'authors')
            ->whereNotIn('id', $booksWithPending)
            ->paginate(10);

        $activeCount = Requisition::activeCount();
        $last30DaysCount = Requisition::last30DaysCount();
        $returnedTodayCount = Requisition::returnedTodayCount();

        return view('requisitions.index', compact(
            'requisitions',
            'availableBooks',
            'allRequisitions',
            'activeCount',
            'last30DaysCount',
            'returnedTodayCount'
        ));
    }

    public function store(Request $request, Book $book)
    {
        $user = Auth::user();
        // Limit regular users to 3 active requests *Note false positive activeRequisitionsCount method intelephense*
        if (!$user->is_admin && $user->activeRequisitionsCount() >= 3) {
            return redirect()->back()->with('error', 'You can only have 3 active book requests at a time.');
        }
        // Prevent duplicate active requisitions for the same book by the same user
        $activeExists = Requisition::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        if ($activeExists) {
            return redirect()->back()->with('error', 'You already have an active request for this book.');
        }
        // If book is unavailable, add user to waitlist and show friendly warning
        if ($book->copies < 1) {
            $alreadyWaitlisted = \App\Models\BookWaitlist::where('user_id', $user->id)
                ->where('book_id', $book->id)
                ->exists();
            if (!$alreadyWaitlisted) {
                \App\Models\BookWaitlist::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                ]);
            }
            return redirect()->back()->with('error', 'This book is currently not available. You will be notified when it is returned.');
        }
        // Prevent any user from requesting a book that is already in a pending requisition
        $pendingExists = Requisition::where('book_id', $book->id)
            ->where('status', 'pending')
            ->exists();
        if ($pendingExists) {
            return redirect()->back()->with('error', 'This book is already being requested by another user.');
        }
        $now = now();
        $requisition = Requisition::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'pending',
            'requested_at' => $now,
            'expected_end_at' => $now->copy()->addDays(5),
        ]);

        // Load relationships for the notification
        $requisition->load(['book.authors', 'book.publisher', 'user']);

        // Notify the user
        $user->notify(new RequisitionCreatedNotification($requisition));

        // Notify all admins
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            $admin->notify(new RequisitionCreatedNotification($requisition));
        }

        $this->logger->log(
            module: 'requisition',
            objectId: $requisition->id,
            description: 'Created requisition for book ID '.$book->id,
            request: $request,
            user: $user,
        );

        return redirect()->back()->with('success', 'Book request submitted successfully!');
    }

    /**
     * Handle returning a book for a requisition.
     */
    public function return(Request $request, Requisition $requisition)
    {
        $user = Auth::user();
        // Only allow the owner or an admin to return
        if ($requisition->user_id !== $user->id && !$user->is_admin) {
            return redirect()->back()->with('error', 'You are not authorized to return this book.');
        }
        // Only allow return if status is 'approved'
        if ($requisition->status !== 'approved') {
            return redirect()->back()->with('error', 'This requisition cannot be returned.');
        }
        // Prevent double returns
        if ($requisition->status === 'returned') {
            return redirect()->back()->with('error', 'This book has already been returned.');
        }
        // Atomic update
        $notified = false;
        DB::transaction(function () use ($requisition, &$notified) {
            $requisition->status = 'returned';
            $requisition->save();
            $book = $requisition->book;
            $wasZero = $book->copies === 0;
            $book->copies = $book->copies + 1;
            $book->save();
            // If book was unavailable and now available, notify waitlist
            if ($wasZero && $book->copies > 0) {
                $waitlist = \App\Models\BookWaitlist::where('book_id', $book->id)->get();
                foreach ($waitlist as $entry) {
                    $entry->user->notify(new \App\Notifications\BookAvailableNotification($book));
                }
                \App\Models\BookWaitlist::where('book_id', $book->id)->delete();
                $notified = true;
            }
        });
        $msg = 'Book returned successfully!';
        if ($notified) {
            $msg .= ' All interested users have been notified.';
        }

        $this->logger->log(
            module: 'requisition',
            objectId: $requisition->id,
            description: 'Returned requisition for book ID '.$requisition->book_id,
            request: $request,
            user: $user,
        );

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Admin approves a pending requisition and decrements book copies.
     */
    public function approve(Request $request, Requisition $requisition)
    {
        $user = Auth::user();
        if (!$user->is_admin) {
            return redirect()->back()->with('error', 'Only admins can approve requisitions.');
        }
        if ($requisition->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending requisitions can be approved.');
        }
        $book = $requisition->book;
        if ($book->copies < 1) {
            return redirect()->back()->with('error', 'No available copies to approve this requisition.');
        }
        DB::transaction(function () use ($requisition, $book) {
            $requisition->status = 'approved';
            $requisition->save();
            $book->copies = $book->copies - 1;
            $book->save();
            // Notify user their requisition was approved
            $requisition->user->notify(new \App\Notifications\RequisitionApprovedNotification($book));
        });

        $this->logger->log(
            module: 'requisition',
            objectId: $requisition->id,
            description: 'Approved requisition for book ID '.$book->id,
            request: $request,
            user: $user,
        );

        return redirect()->back()->with('success', 'Requisition approved and book loaned!');
    }

    public function show(Requisition $requisition)
    {
        $requisition->load('book', 'review');
        return view('requisitions.show', compact('requisition'));
    }
}
