<?php

use App\Models\Book;
use App\Models\Requisition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('allows a user to create a book request', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create();

    actingAs($user)
        ->post(route('books.requisitions.store', $book))
        ->assertRedirect();

    $this->assertDatabaseHas('requisitions', [
        'user_id' => $user->id,
        'book_id' => $book->id,
        'status' => 'pending',
    ]);
});

it('fails to create a request for an invalid book', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post(route('books.requisitions.store', ['book' => 999999]))
        ->assertNotFound();

    $this->assertDatabaseCount('requisitions', 0);
});

it('allows a user to return an approved book request', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create(['copies' => 0]);
    $requisition = Requisition::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'status' => 'approved',
        'requested_at' => now()->subDays(2),
        'expected_end_at' => now()->addDays(3),
    ]);

    actingAs($user)
        ->post(route('requisitions.return', $requisition))
        ->assertRedirect();

    $this->assertDatabaseHas('requisitions', [
        'id' => $requisition->id,
        'status' => 'returned',
    ]);

    $this->assertDatabaseHas('books', [
        'id' => $book->id,
        'copies' => 1,
    ]);
});

it('lists only the authenticated user requisitions', function () {
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $bookA = Book::factory()->create();
    $bookB = Book::factory()->create();

    Requisition::create([
        'user_id' => $userA->id,
        'book_id' => $bookA->id,
        'status' => 'pending',
        'requested_at' => now(),
        'expected_end_at' => now()->addDays(5),
    ]);

    Requisition::create([
        'user_id' => $userB->id,
        'book_id' => $bookB->id,
        'status' => 'pending',
        'requested_at' => now(),
        'expected_end_at' => now()->addDays(5),
    ]);

    $response = actingAs($userA)->get(route('requisitions.index'));

    $response->assertOk();
    $response->assertViewHas('requisitions', function ($paginator) use ($userA, $userB) {
        $userIds = $paginator->pluck('user_id')->unique();
        return $userIds->count() === 1 && $userIds->first() === $userA->id && !$userIds->contains($userB->id);
    });
});

it('prevents requesting a book with zero stock', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create(['copies' => 0]);

    actingAs($user)
        ->post(route('books.requisitions.store', $book))
        ->assertRedirect()
        ->assertSessionHas('error');

    $this->assertDatabaseMissing('requisitions', [
        'user_id' => $user->id,
        'book_id' => $book->id,
        'status' => 'pending',
    ]);
});