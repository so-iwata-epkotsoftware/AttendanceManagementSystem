<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'start_time',
        'end_time',
        'break_time',
        'work_hours',
    ];

    protected $appends = [
        'start_in_time',
        'end_out_time',
        'work_hours_time',
        'break',
    ];

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // アセット
    protected function startInTime(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->start_time)->format('H時i分'),
        );
    }

    protected function endOutTime(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->end_time)->format('H時i分'),
        );
    }

    protected function workHoursTime(): Attribute
    {
        return Attribute::make(
            get: fn () =>Carbon::createFromTime(0, 0, 0)->addSeconds($this->work_hours * 3600)->format('H時間i分'),
        );
    }

    protected function Break(): Attribute
    {
        return Attribute::make(
            get: fn () =>Carbon::createFromTime(0, 0, 0)->addSeconds($this->break_time * 3600)->format('H時間i分'),
        );
    }
}
