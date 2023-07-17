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

    public function loginQr(int $userId): array
    {
        try {
            $response = $this->client->post('/login-qr', [
                'userId' => $userId
            ]);

            if ($response->ok()) {
                Log::info('Refectory service response success', ['message' => $response->body()]);
            } else {
                Log::error('Refectory service response error', ['message' => $response->body()]);
            }

            return [
                'code' => 200,
                'status' => 'success',
                'message' => $response->body()
            ];
        } catch (\Throwable $exception) {
            Log::critical('Refectory service error', ['message' => $exception->getMessage()]);
        }

        return [
            'code' => 403,
            'status' => 'false',
            'message' => 'exception'
        ];
    }
}
