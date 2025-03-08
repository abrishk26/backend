<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract the token from the Authorization header
        $token = $request->bearerToken();

        // Log the token for debugging
        Log::info('AdminMiddleware: Token', ['token' => $token]);

        // Validate the token
        if (!$token) {
            Log::warning('AdminMiddleware: Token not provided');
            return response()->json(['message' => 'Token not provided'], 401);
        }

        // Find the token in the database
        $accessToken = PersonalAccessToken::findToken($token);

        if (!$accessToken) {
            Log::warning('AdminMiddleware: Invalid token');
            return response()->json(['message' => 'Invalid token'], 401);
        }

        // Retrieve the authenticated user
        $user = $accessToken->tokenable;

        // Log the user for debugging
        Log::info('AdminMiddleware: User', ['user' => $user]);

        // Check if the user has the 'admin' role
        if ($user->role !== 'admin') {
            Log::warning('AdminMiddleware: Access denied for non-admin user', ['user_id' => $user->id]);
            return response()->json(['message' => 'Access denied'], 403);
        }

        // Proceed to the next middleware or route handler
        return $next($request);
    }
}