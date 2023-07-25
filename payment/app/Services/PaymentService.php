<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class PaymentService
{
    public function __construct()
    {
    }

    public function pay(Request $request): array
    {
        $createHash = $this->createHash($request);

        $statusUrl = $request->get('status') == 'true' ?
            config('services.tagqr.callback.success_url') : config('services.tagqr.callback.failed_url');

        try {
            $response = Http::post($statusUrl, [
                'hash' => $createHash
            ]);

            $responseContent = json_decode($response->body(), true);

            if ($response->ok()) {
                Log::info('Payment service response success', $responseContent);

                $responseMessage = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'TAG-QR payment notification has been made!'
                ];
            } else {
                Log::error('Payment service response error', ['status' => $response->status()]);

                $responseMessage = [
                    'code' => 400,
                    'status' => 'false',
                    'message' => 'TAG-QR payment notification failed!'
                ];
            }
        } catch (\Throwable $exception) {
            Log::critical('Payment service error', [
                'code' => $exception->getCode(),
                'message' => $exception->getTraceAsString()
            ]);

            $responseMessage = [
                'code' => 400,
                'status' => 'false',
                'message' => $exception->getTraceAsString()
            ];
        }

        Log::info('Refectory Service loginQR method close', ['message' => $responseMessage]);

        return $responseMessage;
    }

    private function createHash(Request $request): string
    {
        return sha1(sprintf(
            '%s%s%s%s',
            $request->get('price'),
            $request->get('callback_success_url'),
            $request->get('callback_fail_url'),
            config('services.tagqr.key'),
        ));
    }
}
