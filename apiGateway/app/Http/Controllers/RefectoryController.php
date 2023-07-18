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
        $content = json_decode($request->getContent(), true);

        Log::info('Refectory quee start', $content);

        ProcessRefectory::dispatch($content)->onQueue('refectory');

        return response()->json([
            'code' => 200,
            'status' => 'false',
            'message' => 'Hash valid!'
        ], 200);
    }
}
