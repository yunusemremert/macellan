<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
    }

    public function pay(Request $request): \Illuminate\Http\JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        if (empty($content)) {
            $message = [
                'code' => 400,
                'status' => 'false',
                'message' => 'Bad request!'
            ];

            Log::warning("Payment Service controller message: Empty data", $message);

            return response()->json($message, 400);
        }

        if (!isset($content['price']) || !isset($content['callback_success_url']) || !isset($content['callback_fail_url'])) {
            $message = [
                'code' => 400,
                'status' => 'false',
                'message' => 'Invalid data!'
            ];

            Log::error("Payment Service controller message: Invalid data", $message);

            return response()->json($message, 400);
        }

        Log::info('Payment Service start', $content);

        $response = $this->paymentService->pay($content);

        return response()->json($response, $response['code']);
    }
}
