<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureKeyIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->bearerToken() !== env('ACCEPTED_SECRETS')) {
            $message = [
                'code' => 401,
                'status' => 'false',
                'message' => 'Unauthorized!'
            ];

            Log::error("Key is valid message: Unauthorized", $message);

            return response()->json($message, 401);
        }

        return $next($request);
    }
}
