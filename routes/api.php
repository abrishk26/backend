<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;

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
Route::delete('/books/{id}', [BookController::class, 'destroy']);

Route::get('/users', [UserController::class, 'index']); // GET all users
Route::get('/users/{id}', [UserController::class, 'show']); // GET a single user
Route::post('/users', [UserController::class, 'store'])->middleware('json'); // POST create a new user
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('json'); // PUT update a user
Route::delete('/users/{id}', [UserController::class, 'destroy']); // DELETE a user