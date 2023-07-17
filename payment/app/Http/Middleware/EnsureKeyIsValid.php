<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        //$request->header('Authorization')
        if ($request->bearerToken() !== env('ACCEPTED_SECRETS')) {
            return response()->json([
                'code' => 401,
                'status' => 'false',
                'message' => 'Unauthorized!'
            ], 401);
        }

        return $next($request);
    }
}
