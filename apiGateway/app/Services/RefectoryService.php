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
            $response = $this->client->post('/login-qr', $content);

            if ($response->ok()) {
                Log::info('Refectory service response success', json_decode($response->body(), true));
            } else {
                Log::error('Refectory service response error', ['status' => $response->status()]);
            }
        } catch (\Throwable $exception) {
            Log::critical('Refectory quee error', [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        }
    }
}
