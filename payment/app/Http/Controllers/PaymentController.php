<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    public function pay(Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->paymentService->pay();

        return response()->json($response, $response['code']);
    }
}
