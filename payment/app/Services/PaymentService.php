<?php

namespace App\Services;

final class PaymentService
{
    // TODO : ödeme

    public function __construct()
    {

    }

    public function pay(): array
    {
        return [
            'code' => 200,
            'status' => 'sucess',
            'message' => 'Payment true!'
        ];
    }
}
