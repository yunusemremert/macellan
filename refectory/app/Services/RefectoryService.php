<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class RefectoryService
{
    public function __construct()
    {
    }

    public function loginQr(string $userId): array
    {
        $refectoryCount = $this->getCountDailyRefectory();
        $transitionCount = $this->getCountDailyUserRefectory($userId);

        if (($transitionCount === 0 && $refectoryCount < 50) || ($transitionCount === 1 && $refectoryCount <= 50)) {
            Log::info('Refectory Service loginQR method open', ['id' => $userId]);

            try {
                if (!$transitionCount) {
                    $this->addDailyRefectory($userId);
                } else {
                    $this->updateDailyRefectory($userId, $transitionCount + 1);
                }

                $responseMessage = [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Allowed to enter the dining hall!'
                ];
            } catch (\Throwable $exception) {
                Log::critical('Payment service error', [
                    'code' => $exception->getCode(),
                    'message' => $exception->getTraceAsString()
                ]);

                $responseMessage = [
                    'code' => 400,
                    'status' => 'false',
                    'message' => $exception->getTraceAsString()
                ];
            }
        } else {
            if ($transitionCount == 2) {
                $responseMessage = [
                    'code' => 200,
                    'status' => 'false',
                    'message' => 'The dining hall entrance limit has been exceeded!'
                ];
            } else {
                $responseMessage = [
                    'code' => 200,
                    'status' => 'false',
                    'message' => 'The total dining hall limit has been exceeded!'
                ];
            }
        }

        Log::info('Refectory Service loginQR method close', ['message' => $responseMessage]);

        return $responseMessage;
    }

    private function getCountDailyRefectory(): int
    {
        $totalDailyCountCache = Cache::get("total:daily_count");

        if ($totalDailyCountCache === null || $totalDailyCountCache < 50) {
            $totalDailyCount = DB::table('daily_passes')
                ->whereDate('transition_date', now()->format('Y-m-d'))
                ->distinct()
                ->count();

            Cache::put("total:daily_count", $totalDailyCount, 60);

            return $totalDailyCount;
        }

        return $totalDailyCountCache;
    }

    private function getCountDailyUserRefectory(string $userId): int
    {
        $dailyCountCache = Cache::get("user:daily_count:$userId");

        if ($dailyCountCache === null || $dailyCountCache < 2) {
            $dailyCountUser = DB::table('daily_passes')
                ->select('transition_count')
                ->where('user_id', $userId)
                ->whereDate('transition_date', now()->format('Y-m-d'))
                ->first();

            $dailyCount = $dailyCountUser->transition_count ?? 0;

            Cache::put("user:daily_count:$userId", $dailyCount, 720);

            return $dailyCount;
        }

        return $dailyCountCache;
    }

    private function addDailyRefectory(string $userId): void
    {
        DB::table('daily_passes')->insert([
            'user_id' => $userId,
            'transition_date' => now()->format('Y-m-d'),
            'created_at' => now()
        ]);
    }

    private function updateDailyRefectory(string $userId, int $transitionCount): void
    {
        DB::table('daily_passes')
            ->where('user_id', $userId)
            ->where('transition_date', now()->format('Y-m-d'))
            ->update([
                'transition_count' => $transitionCount,
                'updated_at' => now()
            ]);
    }
}
