<?php

namespace App\Jobs;

use App\Services\PaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPayment implements ShouldQueue
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
        Log::info('Payment quee in service start', $this->content);

        $paymentService = new PaymentService();
        $paymentService->pay($this->content);
    }

    public function failed(\Throwable $exception): void
    {
        Log::emergency('The payment queue system is down!', ['message' => $exception->getMessage()]);
    }
}
