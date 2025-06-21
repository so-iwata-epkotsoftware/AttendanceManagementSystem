<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttendanceRequest extends FormRequest
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

    const STATUS = [
        'pending', # 未承認
        'approved', # 承認
        'rejected', # 否認
    ];


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'        => ['required', 'exists:users,id'],
            'company_id'     => ['required','exists:companies,id'],
            'clock_in'       => ['nullable', 'date_format:H:i', 'before_or_equal:clock_out'],
            'clock_out'      => ['nullable', 'date_format:H:i', 'after:clock_in'],
            'break_minutes'  => ['nullable','integer','min:0','max:180'],
            'vacation_type'  => ['required', Rule::in(self::ATTENDANCE_CLASSIFICATION)],
            'reason'         => ['nullable','string','max:1000',],
            'status'         => ['required', Rule::in(self::STATUS)],
        ];
    }
}
