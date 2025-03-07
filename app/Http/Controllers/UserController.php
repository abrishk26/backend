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
		$request->validate([
			'email' => 'required|string|email',
			'password' => 'required|string',
		]);

		$user = User::where('email', $request->json('email'))->first();

		if (!$user || !Hash::check($request->json('password'), $user->password)) {
			return response()->json(['message' => 'Invalid credentials'], 401);
		}

		$token = $user->createToken('auth_token')->plainTextToken;

		return response()->json(['message' => 'Login successful'], 200)->header('Authorization', 'Bearer ' . $token);
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
