<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureHashIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (empty($request->all())) {
            $message = [
                'code' => 400,
                'status' => 'false',
                'message' => 'Bad request!'
            ];

            $this->logMessage("Hash Is Valid Message: Empty Data", $message, 0);

            return response()->json($message, 400);
        }

        if (
            (!$request->has('hash') || empty($request->get('hash'))) ||
            (!$request->has('price') || empty($request->get('price'))) ||
            (!$request->has('callback_success_url') || empty($request->get('callback_success_url'))) ||
            (!$request->has('callback_fail_url') || empty($request->get('callback_fail_url')))
        ) {
            $message = [
                'code' => 400,
                'status' => 'false',
                'message' => 'Invalid data!'
            ];

            $this->logMessage("Hash Is Valid Message: Invalid Data", $message, 0);

            return response()->json($message, 400);
        }

        if (!$this->hashCheck($request)) {
            $message = [
                'code' => 403,
                'status' => 'false',
                'message' => 'Hash invalid!'
            ];

            $this->logMessage("Hash Is Valid Message: Hash Invalid", $message, 1);

            return response()->json($message, 403);
        }

        return $next($request);
    }

    private function hashCheck(Request $request): bool
    {
        $cHash = sha1(sprintf(
            '%s%s%s%s',
            config('services.tagqr.key'),
            $request->get('callback_fail_url'),
            $request->get('callback_success_url'),
            $request->get('price'),
        ));

        return $request['hash'] === $cHash;
    }

    private function logMessage(string $messageContent, array $message, int $level): void
    {
        if ($level == 0) {
            Log::info($messageContent, $message);
        } else {
            Log::error($messageContent, $message);
        }
    }
}
