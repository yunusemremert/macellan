<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class RefectoryService
{
    private \Illuminate\Http\Client\PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('services.refectory.baseUrl'))
            ->withToken(config('services.refectory.secretKey'));
    }

    public function loginQr(array $content): void
    {
        try {
            $response = $this->client->post('/login-qr', [
                'userId' => $content['user_id']
            ]);

            $responseContent = json_decode($response->body(), true);

            if ($response->ok()) {
                Log::info('Refectory service response success', $responseContent);
            } else {
                Log::error('Refectory service response error', ['status' => $response->status()]);
            }
        } catch (\Throwable $exception) {
            Log::critical('Refectory quee error', [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);

            return;
        }

        if ($response->ok() && $responseContent['status'] == 'success') {
            // TODO : paymentService call
        }
    }
}
