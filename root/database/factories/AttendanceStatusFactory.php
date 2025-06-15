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
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attendance_id' => Attendance::factory(),
            'status'        => fake()->randomElement(['pending', 'approved', 'rejected']),
            'user_id'       => User::factory(),  // ステータス更新者
            'reason'        => fake()->optional()->sentence,

        ];
    }
}
