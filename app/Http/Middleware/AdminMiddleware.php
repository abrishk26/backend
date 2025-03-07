<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has the 'admin' role
        $user = $request->user();  // Automatically retrieves the authenticated user via Sanctum

        // If the user is not authenticated or is not an admin, deny access
        if (!$user || $user->role !== 'admin') {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return $next($request);
    }
}
