<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

final class PaymentService
{
    private \Illuminate\Http\Client\PendingRequest $client;

    public function __construct()
    {
        $this->client = Http::baseUrl(config('services.payment.baseUrl'))
            ->withToken(config('services.payment.secretKey'));
    }

    public function pay()
    {
        dd("payment pay");
    }
}
