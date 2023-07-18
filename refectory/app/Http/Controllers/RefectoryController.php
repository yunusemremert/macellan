<?php

namespace App\Http\Controllers;

use App\Services\RefectoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RefectoryController extends Controller
{
    public function __construct(protected RefectoryService $refectoryService)
    {
    }

    public function loginQr(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        if (empty($content)) {
            $message = [
                'code' => 400,
                'status' => 'false',
                'message' => 'Bad request!'
            ];

            Log::warning("Refectory Service controller message: Empty data", $message);

            return response()->json($message, 400);
        }

        if (!isset($content['userId'])) {
            $message = [
                'code' => 400,
                'status' => 'false',
                'message' => 'Invalid data!'
            ];

            Log::error("Refectory Service controller message: Invalid data", $message);

            return response()->json($message, 400);
        }

        Log::info('Refectory Service start', $content);

        $response = $this->refectoryService->loginQr($content['userId']);

        return response()->json($response, $response['code']);
    }
}
