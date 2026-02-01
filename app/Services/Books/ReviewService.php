<?php

namespace App\Services\Books;

use App\Models\Review;
use App\Models\Requisition;
use App\Models\Book;
use App\Models\User;
use App\Notifications\ReviewCreatedNotification;

class ReviewService
{
    /**
     * Create a new review for a requisition.
     */
    public function create(array $data, Book $book, User $user, Requisition $requisition): Review
    {
        $review = Review::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'requisition_id' => $requisition->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'status' => Review::STATUS_SUSPENDED,
        ]);

        // Load relationships for the notification
        $review->load(['book', 'user', 'requisition']);

        // Notify all admins about the new review
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            $admin->notify(new ReviewCreatedNotification($review));
        }

        return $review;
    }

    /**
     * Update an existing review.
     */
    public function update(Review $review, array $data): Review
    {
        $review->update([
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
        return $review;
    }

    /**
     * Delete a review.
     */
    public function delete(Review $review): void
    {
        $review->delete();
    }

    /**
     * Get featured/recent reviews for display.
     */
    public function getFeaturedReviews(int $limit = 5)
    {
        return Review::with(['book', 'user'])
            ->where('status', Review::STATUS_ACTIVE)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Approve a review (change status to active).
     */
    public function approve(Review $review): Review
    {
        $review->update(['status' => Review::STATUS_ACTIVE]);
        return $review;
    }

    /**
     * Reject a review (change status to rejected).
     */
    public function reject(Review $review, ?string $reason = null): Review
    {
        $review->update([
            'status' => Review::STATUS_REJECTED,
            'rejection_reason' => $reason,
        ]);
        return $review;
    }
}
