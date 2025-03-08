<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
	// GET all users
	public function index()
	{
		return response()->json(User::all());
	}

	public function show($id)
	{
		$user = User::find($id);

		if ($user) {
			return response()->json($user);
		}

		return response()->json(['message' => 'User not found'], 404);
	}

	// POST create a new user
	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|unique:users',
			'password' => 'required|string',
		]);

		Log::info('Creating a new user', ['email' => $request->json('email')]);
		$user = User::create([
			'name' => $request->json('name'),
			'email' => $request->json('email'),
			'password' => Hash::make($request->json('password')),
		]);

		return response()->json($user, 201);
	}

	public function login(Request $request)
{
    // Validate the request
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    // Find the user by email
    $user = User::where('email', $request->input('email'))->first();

    // Check if the user exists and the password is correct
    if (!$user || !Hash::check($request->input('password'), $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Create a new token for the user
    $tokenResult = $user->createToken('auth_token');
    $plainTextToken = $tokenResult->plainTextToken;

    // Extract the token ID from the plain text token
    $tokenId = explode('|', $plainTextToken)[0];

    // Include user information in the response (excluding sensitive data)
    $userInfo = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role, // Assuming 'role' is a field in your User model
    ];

    // Return the response with the token and user information
    return response()->json([
        'message' => 'Login successful',
        'user' => $userInfo,
        'token' => $plainTextToken, // Include the full token in the response body
        'token_id' => $tokenId, // Include the token ID as a UUID
        'token_type' => 'Bearer', // Specify the token type
    ], 200)->header('Authorization', 'Bearer ' . $plainTextToken); // Also set the token in the header
}

	public function update(Request $request, $id)
	{
		$user = User::find($id);

		if (!$user) {
			return response()->json(['message' => 'User not found'], 404);
		}

		$user->update($request->json()->all());

		return response()->json($user);
	}

	// DELETE a user by ID
	public function destroy($id)
	{
		$user = User::find($id);

		if (!$user) {
			return response()->json(['message' => 'User not found'], 404);
		}

		$user->delete();

		return response()->json(['message' => 'User deleted successfully']);
	}
}
