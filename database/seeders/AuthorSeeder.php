<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::firstOrCreate([
            'name' => 'J.K. Rowling',
            'picture' => 'authors/jkrowling.jpg',
        ]);
        Author::firstOrCreate([
            'name' => 'J.R.R. Tolkien',
            'picture' => 'authors/jrtolkien.jpg',
        ]);
        Author::firstOrCreate([
            'name' => 'C.S. Lewis',
            'picture' => 'authors/cslewis.jpg',
        ]);
    }
}
