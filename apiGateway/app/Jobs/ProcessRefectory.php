<?php

namespace App\Jobs;

use App\Services\RefectoryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessRefectory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $refectoryService = new RefectoryService();

        $response = $refectoryService->loginQr($this->userId);

        Log::info('Refectory service call', ['message' => $response]);
    }

    public function failed(Throwable $exception): void
    {
        Log::emergency('The refectory quee system is down!', ['message' => $exception->getMessage()]);
    }
}
