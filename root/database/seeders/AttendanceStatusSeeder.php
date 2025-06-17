<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\AttendanceStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attendance = Attendance::select('id')->get();

        for ($i=0; $i < count($attendance); $i++)
        {
            // 勤怠ステータスのデータを作成
            AttendanceStatus::factory()->create([
                'user_id' => 2,
                'attendance_id' => $attendance[$i],
            ]);
        }
    }
}
