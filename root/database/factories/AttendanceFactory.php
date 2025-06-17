<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'        => 2, #User::factory(),
            'company_id'     => 1, # Company::factory(),
            'clock_in'       => fake()->dateTimeThisMonth,
            'clock_out'      => fake()->dateTimeThisMonth,
            'work_hours'     => fake()->randomFloat(2, 7, 12),
            'overtime_hours' => fake()->randomFloat(2, 0, 3),
            // 'status'         => fake()->randomElement(['pending', 'approved', 'rejected']),
            'break_minutes'  => fake()->numberBetween(0, 60),

        ];
    }
}
