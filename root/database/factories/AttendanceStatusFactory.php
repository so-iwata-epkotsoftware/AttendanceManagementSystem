<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttendanceStatus>
 */
class AttendanceStatusFactory extends Factory
{
    const STATUS = [
        'pending', # 未承認
        'approved', # 承認
        'rejected', # 否認
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attendance_id' => Attendance::inRandomOrder()->value('id'),
            'status'        => fake()->randomElement(self::STATUS),
            'user_id'       => 2, # User::factory(),  // ステータス更新者
            'reason'        => fake()->optional()->sentence,
        ];
    }
}
