<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceStatusFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'reason',
        'status',
    ];

    protected $appends = ['status_jp'];

    public function attendance()
    {
        return $this->BelongsTo(Attendance::class);
    }

    public function user()
    {
        return $this->BelongsTo(User::class);
    }

    protected function statusJp(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status)
                        {
                            'pending' => '未承認',
                            'approved' => '承認',
                            'rejected' => '否認',
                        },
        );
    }
}
