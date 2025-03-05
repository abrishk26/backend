<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users,email',
			'password' => 'required|string|min:8|confirmed',
		]);

		$user = User::create([
			'name' => $request->json('name'),
			'email' => $request->json('email'),
			'password' => Hash::make($request->json('password')),
		]);

		return response()->json($user, 201);
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
