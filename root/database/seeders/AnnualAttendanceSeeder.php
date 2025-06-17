<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Attendance;

class AnnualAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 2;
        $companyId = 1;
        $startDate = Carbon::create(2024, 1, 1);
        $endDate = Carbon::create(2024, 12, 31);

        while ($startDate->lte($endDate)) {
            // 土日を除く平日のみ作成
            if (!in_array($startDate->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $clockIn = $startDate->copy()->setTime(9, 0);
                $clockOut = $startDate->copy()->setTime(18, 0);
                $breakMinutes = 60;
                $workHours = 8.0;
                $overtimeHours = fake()->randomFloat(2, 0, 2); // 最大2時間残業

                Attendance::create([
                    'user_id'        => $userId,
                    'company_id'     => $companyId,
                    'clock_in'       => $clockIn,
                    'clock_out'      => $clockOut,
                    'work_hours'     => $workHours + $overtimeHours,
                    'overtime_hours' => $overtimeHours,
                    'break_minutes'  => $breakMinutes,
                    'status'         => 'approved',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            $startDate->addDay();
        }

        $total = Attendance::where('user_id', $userId)->count();
        $this->command->info("✅ {$total} 日分の勤怠データを作成しました（平日のみ）");
    }
}
