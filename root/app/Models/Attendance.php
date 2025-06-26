<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;

    protected $fillable = [
        'date',
        'user_id',
        'company_id',
        'clock_in',
        'clock_out',
        'work_hours',
        'overtime_hours',
        'break_minutes'
    ];

    protected $appends = [
        'clock_in_time',
        'clock_out_time',
        'work_hours_time',
        'overtime_hours_time',
    ];

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function attendance_status()
    {
        return $this->hasOne(AttendanceStatus::class);
    }

    public function attendance_history()
    {
        return $this->hasMany(AttendanceHistory::class);
    }

    public function vacation()
    {
        return $this->hasOne(Vacation::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }


    // アセット
    protected function clockInTime(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->clock_in)->format('H時i分'),
        );
    }

    protected function clockOutTime(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->clock_out)->format('H時i分'),
        );
    }

    protected function workHoursTime(): Attribute
    {
        return Attribute::make(
            get: fn () =>Carbon::createFromTime(0, 0, 0)->addSeconds($this->work_hours * 3600)->format('H時i分'),
        );
    }

    protected function overtimeHoursTime(): Attribute
    {
        return Attribute::make(
            get: fn () =>Carbon::createFromTime(0, 0, 0)->addSeconds($this->overtime_hours * 3600)->format('H時i分'),
        );
    }
}
