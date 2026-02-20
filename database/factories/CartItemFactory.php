<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CartItem>
 */
class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        $book = Book::factory()->create();

        return [
            'cart_id' => Cart::factory(),
            'book_id' => $book->id,
            'quantity' => 1,
            'price' => $book->price,
        ];
    }
}
