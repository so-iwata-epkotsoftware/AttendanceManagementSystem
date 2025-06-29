<?php

namespace App\Http\Requests;

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
            'date'       => ['required', 'date_format:Y/m/d'],
            'user_id'        => ['required', 'exists:users,id'],
            'company_id'     => ['required','exists:companies,id'],
            'clock_in'  => ['nullable', 'date_format:H:i', 'before_or_equal:clock_out'],
            'clock_out' => ['nullable', 'date_format:H:i', 'after:clock_in'],
            'break_minutes'  => ['nullable','integer','min:0','max:180'],
            'vacation_type' => ['required', Rule::in(self::ATTENDANCE_CLASSIFICATION)],
            'reason'        => ['nullable','string','max:1000',]
        ];
    }

    public function attributes()
    {
        return [
            'date'           => '日付',
            'clock_in'       => '出勤時間',
            'clock_out'      => '退勤時間',
            'break_minutes'  => '休憩時間',
            'vacation_type'  => '出勤区分',
            'reason'         => '備考'
        ];
    }
}
