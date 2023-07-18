<?php

namespace App\Services;

use App\Jobs\ProcessPayment;
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
                'message' => $exception->getTraceAsString()
            ]);
        }

        $content['status'] = $responseContent['status'] ?? 'false';

        ProcessPayment::dispatch($content)->onQueue('payment');
    }
}
