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
            'logo' => null,
        ]);
        Publisher::firstOrCreate([
            'name' => 'HarperCollins',
            'logo' => null,
        ]);
        Publisher::firstOrCreate([
            'name' => 'Houghton Mifflin',
            'logo' => null,
        ]);
        Publisher::firstOrCreate([
            'name' => 'Allen & Unwin',
            'logo' => null,
        ]);
    }
}
