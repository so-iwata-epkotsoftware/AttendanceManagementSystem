<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceHistory>
 */
class AttendanceHistoryFactory extends Factory
{
    const FIELD_CHANGED = [
        'clock_in', # 出勤時間
        'clock_out', # 退勤時間
        'work_hours', # 実働時間
        'status', # 勤怠ステータス（未承認、承認、拒否）
        'work_hours', # 残業時間
        'break_minutes', # 休憩時間
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attendance_id' => Attendance::factory(),
            'field_changed' => fake()->randomElement(self::FIELD_CHANGED),
            'old_value'     => fake()->text(50),
            'new_value'     => fake()->text(50),
            'user_id'       => User::factory(),

        ];
    }
}
