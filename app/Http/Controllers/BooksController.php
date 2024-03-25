<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            return response()->json($book, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Book not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima dari permintaan
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'author' => 'required|string|max:255',
        ]);

        // Jika validasi gagal, kirimkan respon dengan pesan error
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Buat buku baru dengan menggunakan data yang diterima dari permintaan
        $book = Book::create($request->all());

        // Berikan respons berupa data buku yang berhasil dibuat
        return response()->json($book, 201);
    }

    public function update(Request $request, $id)
    {
        // Validasi request hanya untuk kolom yang diperbarui
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'author' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Temukan buku yang akan diperbarui
        $book = Book::findOrFail($id);

        // Perbarui hanya kolom yang ada dalam request
        if ($request->has('title')) {
            $book->title = $request->title;
        }

        if ($request->has('description')) {
            $book->description = $request->description;
        }

        if ($request->has('author')) {
            $book->author = $request->author;
        }

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
