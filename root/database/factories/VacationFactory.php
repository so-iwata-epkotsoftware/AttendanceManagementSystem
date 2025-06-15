<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vacation>
 */
class VacationFactory extends Factory
{
    const VACATION_TYPE = [
        'paid',
        'sick',
        'unpaid',
        'special'
    ];
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'vacation_type' => fake()->randomElement(self::VACATION_TYPE),
            'start_date'    => fake()->date(),
            'end_date'      => fake()->date(),
            'reason'        => fake()->sentence,
        ];
    }
}
