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
        $content = json_decode($request->getContent(), true);

        if (empty($content)) {
            $message = [
                'code' => 400,
                'status' => 'false',
                'message' => 'Bad request!'
            ];

            $this->logMessage("Hash Is Valid Message: Empty Data", $message, 0);

            return response()->json($message, 400);
        }

        if (!isset($content['hash']) || !isset($content['callback_success_url']) || !isset($content['callback_fail_url'])) {
            $message = [
                'code' => 400,
                'status' => 'false',
                'message' => 'Invalid data!'
            ];

            $this->logMessage("Hash Is Valid Message: Invalid Data", $message, 0);

            return response()->json($message, 400);
        }

        if (!$this->hashCheck($content)) {
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

    private function hashCheck(array $request): bool
    {
        $cHash = sha1(sprintf(
            '%s%s%s%s',
            env('QR_TAG_SALT_KEY'),
            $request['callback_fail_url'],
            $request['callback_success_url'],
            $request['price'],
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
