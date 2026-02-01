<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reviews\ReviewRequest;
use App\Models\Book;
use App\Models\Requisition;
use App\Models\Review;
use App\Services\Books\ReviewService;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Book $book, Requisition $requisition, ReviewService $reviewService)
    {
        $user = Auth::user();
        // Only allow if user is not admin, is the owner, and requisition is returned
        if ($user->is_admin || $requisition->user_id !== $user->id || $requisition->status !== 'returned') {
            abort(403);
        }
        // Only allow one review per requisition per user
        if ($requisition->review) {
            return back()->with('error', 'You have already reviewed this book.');
        }
        $reviewService->create($request->validated(), $book, $user, $requisition);
        return back()->with('success', 'Review submitted successfully!');
    }

    public function update(ReviewRequest $request, Review $review, ReviewService $reviewService)
    {
        $user = Auth::user();
        if ($user->is_admin || $review->user_id !== $user->id) {
            abort(403);
        }
        $reviewService->update($review, $request->validated());
        return back()->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review, ReviewService $reviewService)
    {
        $user = Auth::user();
        if ($user->is_admin || $review->user_id !== $user->id) {
            abort(403);
        }
        $reviewService->delete($review);
        return back()->with('success', 'Review deleted successfully!');
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        $review->load(['book.authors', 'book.publisher', 'user', 'requisition']);
        return view('reviews.show', compact('review'));
    }

    /**
     * Display all reviews for admin moderation.
     */
    public function adminIndex()
    {
        $status = request('status', 'suspended');

        $query = Review::with(['book', 'user', 'requisition']);

        if ($status && in_array($status, ['suspended', 'active', 'rejected'])) {
            $query->where('status', $status);
        }

        $reviews = $query->latest()->paginate(15);

        return view('admin.reviews.index', compact('reviews', 'status'));
    }

    /**
     * Approve a review (change status to active).
     */
    public function approve(Review $review, ReviewService $reviewService)
    {
        $reviewService->approve($review);
        return back()->with('success', 'Review approved successfully!');
    }

    /**
     * Reject a review (change status to rejected).
     */
    public function reject(Review $review, ReviewService $reviewService)
    {
        $reason = request('rejection_reason');
        $reviewService->reject($review, $reason);
        return back()->with('success', 'Review rejected successfully!');
    }
}
