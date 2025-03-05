<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/health-check', function () {
    return response()->json([
	'status' => 'ok',
	'message' => 'The API is working!',
    ]);
});

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::post('/books', [BookController::class, 'store']);
Route::put('/books/{id}', [BookController::class, 'update']);
Route::delete('/books/{id}',[BookController::class, 'destroy']);

