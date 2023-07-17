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

    public function loginQr(Request $request): \Illuminate\Http\JsonResponse
    {
        $content = $request->getContent();
        $item = json_decode($content, true);

        if (empty($item)) {
            return response()->json([
                'code' => 406,
                'status' => 'false',
                'message' => 'Empty data!'
            ], 406);
        }

        if (!isset($item['userId'])) {
            return response()->json([
                'code' => 400,
                'status' => 'false',
                'message' => 'Invalid data!'
            ], 400);
        }

        Log::info('Refectory microservice log: {id}', ['id' => $item['userId']]);

        $response = $this->refectoryService->loginQr($item['userId']);

        dd($response);

        return response()->json([
            'code' => 200,
            'status' => 'true',
            'message' => 'Data processed!'
        ], 200);
    }
}
