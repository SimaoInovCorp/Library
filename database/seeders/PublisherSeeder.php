<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Publisher;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Publisher::firstOrCreate([
            'name' => 'Bloomsbury',
            'logo' => 'publishers/bloomsbury.jpg',
        ]);
        Publisher::firstOrCreate([
            'name' => 'HarperCollins',
            'logo' => 'publishers/harpercollins.jpg',
        ]);
        Publisher::firstOrCreate([
            'name' => 'Houghton Mifflin',
            'logo' => 'publishers/houghtonmifflin.jpg',
        ]);
        Publisher::firstOrCreate([
            'name' => 'Allen & Unwin',
            'logo' => 'publishers/allen_unwin.jpg',
        ]);
    }
}
