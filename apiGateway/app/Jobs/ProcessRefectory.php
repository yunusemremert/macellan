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

    private array $content;

    /**
     * Create a new job instance.
     */
    public function __construct(array $content)
    {
        $this->content = $content;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Refectory quee in service start', $this->content);

        $refectoryService = new RefectoryService();

        $refectoryService->loginQr($this->content);
    }

    public function failed(Throwable $exception): void
    {
        Log::emergency('The refectory queue system is down!', ['message' => $exception->getMessage()]);
    }
}
