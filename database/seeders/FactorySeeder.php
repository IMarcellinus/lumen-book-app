<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class FactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create 50 books using the BookFactory
        Book::factory()->count(50)->create();
    }
}
