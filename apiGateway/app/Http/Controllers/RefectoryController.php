<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessRefectory;
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
        $items = json_decode($content, true);

        if (empty($items)) {
            return response()->json([
                'code' => 406,
                'status' => 'false',
                'message' => 'Empty data!'
            ], 406);
        }

        $userIds = [];

        foreach ($items as $item) {
            if (!isset($item['userId'])) {
                return response()->json([
                    'code' => 400,
                    'status' => 'false',
                    'message' => 'Invalid data!'
                ], 400);
            }

            $userIds[] = $item['userId'];
        }

        $userIds = array_unique($userIds);
        foreach ($userIds as $userId) {
            try {
                Log::info('Refectory quee start for user: {id}', ['id' => $userId]);

                ProcessRefectory::dispatch($userId)->onQueue('refectory');
            } catch (\Throwable $exception) {
                Log::critical('Refectory quee error', ['message' => $exception->getMessage()]);
            }
        }

        return response()->json([
            'code' => 200,
            'status' => 'sucess',
            'message' => 'Processing data!'
        ], 200);
    }
}
