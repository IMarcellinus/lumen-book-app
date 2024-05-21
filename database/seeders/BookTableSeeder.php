<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Author::factory(10)->create()->each(function ($author) {
            $author->books()->saveMany(Book::factory(rand(1, 5))->make());
        });
    }
}
