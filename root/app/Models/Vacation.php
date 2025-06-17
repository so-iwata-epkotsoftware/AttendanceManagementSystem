<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    /** @use HasFactory<\Database\Factories\VacationFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'vacation_type',
        'reason'
    ];

    protected $appends = ['vacation_type_jp'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }


    protected function vacationTypeJp(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->vacation_type)
                        {
                            'RegularAttendance' => '通常出勤',
                            'LateArrival' => '遅刻',
                            'EarlyLeaving' => '早退',
                            'Absence' => '欠勤',
                            'PaidLeave' => '有給休暇',
                            'SpecialLeave' => '特別休暇',
                            'HolidayWork' => '休日出勤',
                            'CompensatedLeave' => '振替出勤',
                            'StaggeredWork' => '時差出勤',
                            'Telecommuting' => '在宅勤務',
                            'BusinessTravel' => '出張',
                            'HalfDayPaidMorning' => '半日有給（午前）',
                            'HalfDayPaidAfternoon' => '半日有給（午後）',
                        },
        );
    }
}
