<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class RefectoryService
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    public function loginQr(int $userId): array
    {
        $refectoryCount = $this->getCountDailyRefectory();
        $transitionCount = $this->getCountDailyUserRefectory($userId);

        if (($transitionCount === 0 && $refectoryCount < 50) || ($transitionCount === 1 && $refectoryCount <= 50)) {
            Log::info('Refectory quee insert for user: {id}', ['id' => $userId]);

            return $this->loginQrPayment($userId);
        } else {
            if ($transitionCount == 2) {
                return [
                    'code' => 400,
                    'status' => 'false',
                    'message' => 'The dining hall entrance limit has been exceeded!'
                ];
            } else {
                return [
                    'code' => 400,
                    'status' => 'false',
                    'message' => 'The total dining hall limit has been exceeded!'
                ];
            }
        }
    }

    private function getCountDailyRefectory(): int
    {
        return DB::table('daily_passes')
            ->whereDate('transition_date', now()->format('Y-m-d'))
            ->distinct()
            ->count();
    }

    private function getCountDailyUserRefectory(int $userId): int
    {
        $dailyCountUser = DB::table('daily_passes')
            ->select('transition_count')
            ->where('user_id', $userId)
            ->whereDate('transition_date', now()->format('Y-m-d'))
            ->first();

        return $dailyCountUser->transition_count ?? 0;
    }

    private function addDailyRefectory(int $userId): void
    {
        DB::table('daily_passes')->insert([
            'user_id' => $userId,
            'transition_date' => now()->format('Y-m-d'),
            'created_at' => now()
        ]);
    }

    private function updateDailyRefectory(int $userId, int $transitionCount): void
    {
        DB::table('daily_passes')
            ->where('user_id', $userId)
            ->where('transition_date', now()->format('Y-m-d'))
            ->update([
                'transition_count' => $transitionCount,
                'updated_at' => now()
            ]);
    }

    public function loginQrPayment(int $userId): array
    {
        $paymentResponse = $this->paymentService->pay($userId);

        if ($paymentResponse['code'] == 200) {
            $this->loginQrApprove($userId);
        }

        return $paymentResponse;
    }

    public function loginQrApprove(int $userId): void
    {
        $transitionCount = $this->getCountDailyUserRefectory($userId);

        if (!$transitionCount) {
            $this->addDailyRefectory($userId);
        } else {
            $this->updateDailyRefectory($userId, $transitionCount + 1);
        }
    }
}
