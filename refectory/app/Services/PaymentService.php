<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class PaymentService
{
    private \Illuminate\Http\Client\PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('services.payment.baseUrl'))
            ->withToken(config('services.payment.secretKey'));
    }

    public function pay(int $userId): array
    {
        try {
            $response = $this->client->post('/pay', [
                'userId' => $userId
            ]);

            if ($response->ok()) {
                Log::info('Payment service pay success', ['message' => $response->body()]);
            } else {
                Log::error('Payment service pay error', ['message' => $response->body()]);
            }

            return json_decode($response->body(), true);
        } catch (\Throwable $exception) {
            Log::critical('Refectory service error', ['message' => $exception->getMessage()]);
        }

        return [
            'code' => 400,
            'status' => 'false',
            'message' => 'Qr payment failed!'
        ];
    }
}
