<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create publishers
        $bloomsbury = Publisher::firstOrCreate(['name' => 'Bloomsbury']);
        $harper = Publisher::firstOrCreate(['name' => 'HarperCollins']);
        $houghton = Publisher::firstOrCreate(['name' => 'Houghton Mifflin']);
        $allen = Publisher::firstOrCreate(['name' => 'Allen & Unwin']);

        // Create authors
        $rowling = Author::firstOrCreate(['name' => 'J.K. Rowling']);
        $tolkien = Author::firstOrCreate(['name' => 'J.R.R. Tolkien']);
        $lewis = Author::firstOrCreate(['name' => 'C.S. Lewis']);

        // Create books
        $book1 = Book::create([
            'isbn' => '9780747532743',
            'name' => "Harry Potter and the Philosopher's Stone",
            'bibliography' => 'First book in the Harry Potter series.',
            'cover_image' => 'books/harryPotter.jpg',
            'price' => 19.99,
            'publisher_id' => $bloomsbury->id,
            'copies' => 1,
        ]);
        $book1->authors()->sync([$rowling->id]);

        $book2 = Book::create([
            'isbn' => '9780261103573',
            'name' => 'The Lord of the Rings',
            'bibliography' => 'Epic high-fantasy novel.',
            'cover_image' => 'books/lotr.jpg',
            'price' => 29.99,
            'publisher_id' => $allen->id,
            'copies' => 2,
        ]);
        $book2->authors()->sync([$tolkien->id]);

        $book3 = Book::create([
            'isbn' => '9780064471190',
            'name' => 'The Chronicles of Narnia: The Lion, the Witch and the Wardrobe',
            'bibliography' => 'Classic fantasy novel for children.',
            'cover_image' => 'books/narnia.jpg',
            'price' => 14.99,
            'publisher_id' => $harper->id,
            'copies' => 5,
        ]);
        $book3->authors()->sync([$lewis->id]);

        $book4 = Book::create([
            'isbn' => '9780395177112',
            'name' => 'The Hobbit',
            'bibliography' => 'Fantasy novel and children’s book by J.R.R. Tolkien.',
            'cover_image' => 'books/hobbit.jpg',
            'price' => 17.99,
            'publisher_id' => $houghton->id,
            'copies' => 5,
        ]);
        $book4->authors()->sync([$tolkien->id]);
    }
}
