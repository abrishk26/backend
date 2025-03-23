<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
		// Validate the incoming data
		$validated = $request->validate([
			'title' => 'required|string',
			'author' => 'required|string',
			'published_year' => 'required|integer',
			'description' => 'required|string',
			'genre' => 'required|string',
			'image_data' => 'required|string', // Base64-encoded image string
			'stock' => 'required|integer',
			'price' => 'required|numeric',
		]);

		// Decode the Base64 image and store it
		$imageData = $validated['image_data'];
		$imagePath = $this->storeBase64Image($imageData);

		// Save the image path in the database
		$validated['image_data'] = $imagePath;

		// Create the book
		$book = Book::create($validated);

		return response()->json($book, 201);
	}

	public function update(Request $request, $id)
	{
		// Find the book by ID
		$book = Book::find($id);

		if (!$book) {
			return response()->json(['message' => 'Book not found'], 404);
		}

		// Validate the incoming data
		$validated = $request->validate([
			'title' => 'nullable|string',
			'author' => 'nullable|string',
			'published_year' => 'nullable|integer',
			'description' => 'nullable|string',
			'genre' => 'nullable|string',
			'image_data' => 'nullable|string', // Base64-encoded image string
			'stock' => 'nullable|integer',
			'price' => 'nullable|numeric',
		]);

		// If a new Base64 image is provided, decode and store it
		if (isset($validated['image_data'])) {
			// Delete the old image file if it exists
			if ($book->image_data) {
				Storage::disk('public')->delete($book->image_data);
			}

			// Decode the Base64 image and store it
			$imageData = $validated['image_data'];
			$imagePath = $this->storeBase64Image($imageData);
			$validated['image_data'] = $imagePath;
		}

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

		// Delete the image file if it exists
		if ($book->image_data) {
			Storage::disk('public')->delete($book->image_data);
		}

		$book->delete();

		return response()->json(['message' => 'Book deleted successfully']);
	}

	/**
	 * Helper function to store a Base64-encoded image and return the file path.
	 *
	 * @param string $base64Image
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	private function storeBase64Image(string $base64Image): string
	{
		// Check if the Base64 string includes the prefix
		if (strpos($base64Image, 'data:image/') === 0) {
			// Extract the image type and data from the Base64 string
			if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
				$imageType = $matches[1]; // e.g., jpeg, png, etc.
				$imageData = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));
			} else {
				throw new \InvalidArgumentException('Invalid Base64 image format');
			}
		} else {
			// Assume the Base64 string is raw data (without prefix)
			$imageData = base64_decode($base64Image);
			$imageType = $this->detectImageType($imageData); // Detect the image type
		}

		// Generate a unique file name
		$fileName = Str::uuid() . '.' . $imageType;

		// Store the image in the public disk
		Storage::disk('public')->put("images/{$fileName}", $imageData);

		// Return the file path
		return "images/{$fileName}";
	}

	/**
	 * Detect the image type from raw image data.
	 *
	 * @param string $imageData
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	private function detectImageType(string $imageData): string
	{
		$finfo = new \finfo(FILEINFO_MIME_TYPE);
		$mimeType = $finfo->buffer($imageData);

		switch ($mimeType) {
			case 'image/jpeg':
				return 'jpg';
			case 'image/png':
				return 'png';
			case 'image/gif':
				return 'gif';
			default:
				throw new \InvalidArgumentException('Unsupported image type');
		}
	}
}
