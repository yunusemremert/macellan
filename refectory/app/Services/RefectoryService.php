<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class RefectoryService
{
    public function loginQr(int $userId): array
    {
        $refectoryCount = $this->getCountDailyRefectory();
        $transitionCount = $this->getCountDailyUserRefectory($userId);

        if ($transitionCount === 0 && $refectoryCount < 50) {
            try {
                $this->addDailyRefectory($userId);

                Log::info('Refectory quee insert for user: {id}', ['id' => $userId]);

                return [
                    'code' => 200,
                    'status' => 'true',
                    'message' => 'Data processed!'
                ];
            } catch (\Throwable $exception) {
                return [
                    'code' => 400,
                    'status' => 'false',
                    'message' => $exception->getMessage()
                ];
            }
        } else if ($transitionCount === 1 && $refectoryCount <= 50) {
            try {
                $this->updateDailyRefectory($userId, $transitionCount + 1);

                Log::info('Refectory quee update for user: {id}', ['id' => $userId]);

                return [
                    'code' => 200,
                    'status' => 'true',
                    'message' => 'Data processed!'
                ];
            } catch (\Throwable $exception) {
                return [
                    'code' => 400,
                    'status' => 'false',
                    'message' => $exception->getMessage()
                ];
            }
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
}
