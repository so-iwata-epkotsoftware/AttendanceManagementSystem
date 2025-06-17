<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vacation>
 */
class VacationFactory extends Factory
{
    const ATTENDANCE_CLASSIFICATION = [
        'RegularAttendance', # 通常出勤
        'LateArrival', # 遅刻
        'EarlyLeaving', # 早退
        'Absence', # 欠勤
        'PaidLeave', # 有給休暇
        'SpecialLeave', # 特別休暇
        'HolidayWork', # 休日出勤
        'CompensatedLeave', # 振替出勤
        'StaggeredWork', # 時差出勤	　
        'Telecommuting', # 在宅勤務
        'BusinessTravel', # 出張
        'HalfDayPaidMorning', # 半日有給（午前）
        'HalfDayPaidAfternoon', # 半日有給（午後）
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 year', 'now');
        $end   = (clone $start)->modify('+'.rand(0, 5).' days');

        return [
            'user_id'       => 2, // fallback if no user
            'attendance_id' => Attendance::inRandomOrder()->value('id'),
            'vacation_type' => $this->faker->randomElement(self::ATTENDANCE_CLASSIFICATION),
            'reason'        => $this->faker->optional()->sentence,
        ];
    }
}
