<?php
// tests/Unit/CartServiceTest.php

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('can create a cart with an item', function () {
    $book = Book::factory()->create();
    $cart = Cart::factory()->create();
    $item = $cart->items()->create([
        'book_id' => $book->id,
        'quantity' => 1,
        'price' => $book->price,
    ]);
    expect($item->book_id)->toBe($book->id);
});

it('returns empty array if all cart items are available', function () {
    $book = Book::factory()->create(['copies' => 3]);
    $cart = Cart::factory()->create();
    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'book_id' => $book->id,
        'quantity' => 2,
    ]);
    $service = app(CartService::class);
    $result = $service->validateCartAvailability($cart->fresh('items.book'));
    expect($result)->toBeArray()->toBeEmpty();
});

it('returns unavailable items if requested quantity exceeds stock', function () {
    $book = Book::factory()->create(['copies' => 1]);
    $cart = Cart::factory()->create();
    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'book_id' => $book->id,
        'quantity' => 2,
    ]);
    $service = app(CartService::class);
    $result = $service->validateCartAvailability($cart->fresh('items.book'));
    expect($result)->not->toBeEmpty();
    expect($result[0]['book']->id)->toBe($book->id);
    expect($result[0]['requested'])->toBe(2);
    expect($result[0]['available'])->toBe(1);
});

it('returns unavailable items if book is out of stock', function () {
    $book = Book::factory()->create(['copies' => 0]);
    $cart = Cart::factory()->create();
    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'book_id' => $book->id,
        'quantity' => 1,
    ]);
    $service = app(CartService::class);
    $result = $service->validateCartAvailability($cart->fresh('items.book'));
    expect($result)->not->toBeEmpty();
    expect($result[0]['book']->id)->toBe($book->id);
    expect($result[0]['requested'])->toBe(1);
    expect($result[0]['available'])->toBe(0);
});