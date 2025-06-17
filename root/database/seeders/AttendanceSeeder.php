<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 2;
        $companyId = 1;
        $year = date('Y');

        // 1月〜12月ループ
        for ($month = 1; $month <= 12; $month++) {
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();

            while ($startDate->lte($endDate)) {
                // 18時～23時の間でランダムな時・分を生成
                $randomHour = rand(18, 23);
                $randomMinute = rand(0, 59);
                
                // 土日を除く平日のみ作成
                if (!in_array($startDate->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                    $clockIn = $startDate->copy()->setTime(9, 0);
                    $clockOut = $startDate->copy()->setTime($randomHour, $randomMinute);
                    $breakMinutes = 60;

                    $totalMinutes = $clockIn->diffInMinutes($clockOut);
                    $workHours = ($totalMinutes - $breakMinutes) / 60;;
                    $overtimeHours = $workHours - 8.0;

                    Attendance::create([
                        'user_id'        => $userId,
                        'company_id'     => $companyId,
                        'clock_in'       => $clockIn,
                        'clock_out'      => $clockOut,
                        'work_hours'     => $workHours,
                        'overtime_hours' => $overtimeHours,
                        'break_minutes'  => $breakMinutes,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }

                $startDate->addDay();
            }
        }
    }
}
