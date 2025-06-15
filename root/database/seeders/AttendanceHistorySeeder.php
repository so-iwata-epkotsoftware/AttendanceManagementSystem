<?php

namespace Database\Seeders;

use App\Models\AttendanceHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 勤怠変更履歴を作成
        AttendanceHistory::factory(100)->create();
    }
}
