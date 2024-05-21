<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Transformer\BookTransformer;

class BooksController extends Controller
{
    /**
     * GET /books
     * @return array
     */
    public function index()
    {
        $books = Book::all();
        $transformedBooks = [];

        foreach ($books as $book) {
            $transformedBooks[] = [
                'id' => $book->id,
                'title' => $book->title,
                'description' => $book->description,
                'author' => $book->author,
                'created' => $book->created_at->toIso8601String(),
                'updated' => $book->updated_at->toIso8601String(),
            ];
        }

        return response()->json(['data' => $transformedBooks]);

    }

    public function show($id)
    {
        return $this->item(Book::findOrFail($id), new BookTransformer());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'author_id' => 'required|exists:authors,id'
        ], [
            'description.required' => 'Please provide a :attribute.'
        ]);
        $book = Book::create($request->all());
        $data = $this->item($book, new BookTransformer());

        return response()->json($data, 201, [
            'Location' => route('books.show', ['id' => $book->id])
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $book = Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
            ], 404);
        }
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'author_id' => 'required|exists:authors,id'
        ], [
            'description.required' => 'Please provide a :attribute.'
        ]);

        $book->fill($request->all());
        $book->save();

        return $this->item($book, new BookTransformer());
    }

    public function destroy($id)
    {
        try {
            $book = Book::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
            ], 404);
        }

        $book->delete();
        return response(null, 204);
    }

    public function destroyall()
    {
        $book = Book::truncate();
        // $book->delete();x
        return response(null, 204);
    }
}
