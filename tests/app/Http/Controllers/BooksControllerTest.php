<?php

namespace Tests;

use App\Models\Book;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class BooksControllerTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndexShouldReturnACollectionOfRecords()
    {
        $books = Book::factory()->count(2)->create();

        $response = $this->get('/books');

        foreach ($books as $book) {
            $response->seeJson(['title' => $book->title]);
        }
    }

    public function test_show_should_return_a_valid_book()
    {
        // Create a book using factory
        $book = Book::factory()->create();

        // Hit the show route
        $response = $this->get("/books/{$book->id}");

        // Assert response status code
        $response->assertResponseStatus(200);

        $response->assertJson(json_encode([
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
        ]));
    }

    public function testUpdateShouldOnlyChangeFillableFields()
    {
        $book = Book::factory()->create([
            'title' => 'War of the Worlds',
            'description' => 'A science fiction masterpiece about Martians invading London',
            'author' => 'H. G. Wells',
        ]);

        $response = $this->put("/books/{$book->id}", [
            'title' => 'The War of the Worlds',
            'description' => 'The book is way better than the movie.',
            'author' => 'Wells, H. G.'
        ]);

        $response->assertResponseStatus(200);

        $response->assertJson(json_encode([
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
        ]));

        $response->seeInDatabase('books', [
            'title' => 'The War of the Worlds'
        ]);

        // Assert the book with id 5 is not in the database
        $response->missingFromDatabase('books', [
            'id' => 5
        ]);
    }

    public function test_destroy_should_remove_a_valid_book()
    {
        $book = Book::factory()->create(); // Membuat buku baru menggunakan factory

        $this->delete("/books/{$book->id}") // Mengirim permintaan DELETE untuk menghapus buku
            ->assertResponseStatus(200); // Memastikan respons status adalah 204 (No Content)

        $this->missingFromDatabase('books', ['id' => $book->id]); // Memastikan bahwa buku telah dihapus dari basis data
    }

}
