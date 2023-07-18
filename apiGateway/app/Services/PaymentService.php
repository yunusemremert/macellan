<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class PaymentService
{
    private \Illuminate\Http\Client\PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('services.refectory.baseUrl'))
            ->withToken(config('services.refectory.secretKey'));
    }

    public function pay(array $content): void
    {
        try {
            $response = $this->client->post('/pay', $content);

            $responseContent = json_decode($response->body(), true);

            if ($response->ok()) {
                Log::info('Payment service response success', $responseContent);
            } else {
                Log::error('Payment service response error', ['status' => $response->status()]);
            }
        } catch (\Throwable $exception) {
            Log::critical('Refectory quee error', [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        }
    }
}
