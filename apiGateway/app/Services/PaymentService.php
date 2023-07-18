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

    public function pay(array $content): void
    {
        try {
            $response = $this->client->post('/pay', $content);

            $responseContent = json_decode($response->body(), true);

            if ($response->ok()) {
                Log::info('Payment service response success', $responseContent);
            } else {
                Log::error('Payment service response error', $responseContent);
            }
        } catch (\Throwable $exception) {
            Log::critical('Payment quee error', [
                'code' => $exception->getCode(),
                'message' => $exception->getTraceAsString()
            ]);
        }
    }
}
