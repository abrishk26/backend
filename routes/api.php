<?php

use App\Http\Middleware\EnsureJsonRequest as JsonMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckAuthorizationHeader;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\ValidateToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'The API is working!',
    ]);
});

// Book management
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);

// User management
Route::post('/users/register', [UserController::class, 'store'])->middleware(JsonMiddleware::class); // POST create a new user
Route::post('/users/login', [UserController::class, 'login'])->middleware(JsonMiddleware::class); // POST login a user

// Apply 'auth:sanctum' middleware to a group of routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Protected routes that require authentication via Sanctum

    // user management
    Route::get('/admin/users', [UserController::class, 'index'])->middleware(AdminMiddleware::class); // GET all users
    Route::get('/admin/users/{id}', [UserController::class, 'show'])->middleware(AdminMiddleware::class); // GET a single user
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->middleware(JsonMiddleware::class)->middleware(AdminMiddleware::class); // PUT update a user
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->middleware(AdminMiddleware::class); // DELETE a user

    // Book management
    Route::post('/admin/books', [BookController::class, 'store'])->middleware(JsonMiddleware::class)->middleware(AdminMiddleware::class);
    Route::put('/admin/books/{id}', [BookController::class, 'update'])->middleware(JsonMiddleware::class)->middleware(AdminMiddleware::class);
    Route::delete('/admin/books/{id}', [BookController::class, 'destroy'])->middleware(AdminMiddleware::class);

    // Order management
    Route::get('/admin/orders', [OrderController::class, 'index'])->middleware(AdminMiddleware::class); // View all orders

    // Admin check
});

Route::get('/admin', [AdminController::class, 'check'])->middleware(CheckAuthorizationHeader::class)->middleware(ValidateToken::class)->middleware(CheckUserRole::class); // Check if user is authorized

