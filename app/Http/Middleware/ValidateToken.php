<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class ValidateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
       
            $token = $request->bearerToken(); // Extract the token from the Authorization header
            Log::info('Token being validated:', ['token' => $token]);
        
            if (!$token) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
            $personalAccessToken = PersonalAccessToken::findToken($token);
            if (!$personalAccessToken) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
            return $next($request);
        }
}
