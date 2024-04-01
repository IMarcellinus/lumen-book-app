<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return array
     */
    public function index()
    {
        return Book::all()->toArray();
    }
    public function show($id)
    {
        try {
            $book = Book::findOrFail($id);
            return response()->json($book);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => ['message' => 'Book not found']], 404);
        }
    }

    public function store(Request $request)
    {
        // Buat buku baru dengan menggunakan data yang diterima dari permintaan
        $book = Book::create($request->all());

        // Berikan respons berupa data buku yang berhasil dibuat
        return response()->json($book, 201);
    }

    public function update(Request $request, $id)
    {
        // Temukan buku yang akan diperbarui
        $book = Book::findOrFail($id);
        $book->fill($request->all());

        // Simpan perubahan
        $book->save();

        // Berikan respons berupa buku yang berhasil diperbarui
        return response()->json($book, 200);
    }

    public function delete($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json(['deleted' => true], 200);
    }
}
