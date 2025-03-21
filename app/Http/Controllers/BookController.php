<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
	// Get all books
	public function index()
	{
		return response()->json(Book::all());
	}

	public function show($id)
	{
		$book = Book::find($id);

		if ($book) {
			return response()->json($book);
		}

		return response()->json(['message' => 'Book not found'], 404);
	}

	public function store(Request $request)
	{
		// Validate the incoming data (including the Base64 image)
		$validated = $request->validate([
			'title' => 'required|string',
			'author' => 'required|string',
			'published_year' => 'required|integer',
			'description' => 'required|string',
			'genre' => 'required|string',
			'image_data' => 'required|string', // Image is expected to be a Base64 string
			'stock' => 'required|integer',
			'price' => 'required|decimal:0,6',
		]);

		// // Decode the Base64 image string to binary data
		// $imageData = $validated['image_data']);


		// // You can store the path to the image in the database
		// $validated['image_data'] = $imageData;  // Save the file path in the database

		// Create the book with the Base64 image data stored as binary
		$book = Book::create($validated);

		return response()->json($book, 201);
	}

	public function update(Request $request,  $id)
	{
		// Find the book by ID
		$book = Book::find($id);
		
		// Validate the incoming data (including the Base64 image)
		$validated = $request->validate([
			'title' => 'nullable|string',
			'author' => 'nullable|string',
			'published_year' => 'nullable|integer',
			'description' => 'nullable|string',
			'genre' => 'nullable|string',
			'image_data' => 'nullable|string', // Image is expected to be a Base64 string
			'stock' => 'nullable|integer',
			'price' => 'nullable|decimal:0,6',
		]);

		// // If an image is provided, decode the Base64 string to binary
		// if (isset($validated['image_data'])) {
		// 	// Decode the Base64 string to binary data
		// 	$imageData = base64_decode($validated['image_data']);

		// 	$validated['image_data'] = $imageData;
		// }

		// Update the book with the new data
		$book->update($validated);

		return response()->json($book);
	}

	public function destroy($id)
	{
		$book = Book::find($id);

		if (!$book) {
			return response()->json(['message' => 'Book not found'], 404);
		}

		$book->delete();

		return response()->json(['message' => 'Book deleted successfully']);
	}
}
