<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class PaymentService
{
    public function __construct()
    {
    }

    public function pay(array $content): array
    {
        $createHash = $this->createHash($content);

        $statusUrl = $content['status'] == 'true' ?
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
                    'code' => 200,
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
                'code' => 200,
                'status' => 'false',
                'message' => $exception->getTraceAsString()
            ];
        }

        Log::info('Refectory Service loginQR method close', ['message' => $responseMessage]);

        return $responseMessage;
    }

    private function createHash(array $content): string
    {
        return sha1(sprintf(
            '%s%s%s%s',
            $content['price'],
            $content['callback_success_url'],
            $content['callback_fail_url'],
            env('TAG_QR_SALT_KEY'),
        ));
    }
}
