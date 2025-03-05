<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Get all books
    public function index() {
		return response()->json(Book::all());
    }

    public function show($id) {
		$book = Book::find($id);

		if ($book) {
			return response()->json($book);
		}

		return response()->json(['message' => 'Book not found'], 404);
    }

    public function store(Request $request) {
		$request->validate([
			'title' => 'required|string|max:255',
			'author' => 'required|string|max:255',
			'published_year' => 'required|integer',
		]);

		$book = Book::create($request->all());
		return response()->json($book, 201);
    }

    public function update(Request $request, $id) {
		$book = Book::find($id);

		if (!$book) {
			return response()->json(['message' => 'Book not found'], 404);
		}

		$book->update($request->all());

		return response()->json($book);
    }

    public function destroy($id) {
		$book = Book::find($id);

		if (!$book) {
			return response()->json(['message' => 'Book not found'], 404);
		}

		$book->delete();

		return response()->json(['message' => 'Book deleted successfully']);
	}
}

