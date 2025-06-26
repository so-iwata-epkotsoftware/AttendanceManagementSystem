<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
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
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','string', 'email', 'max:255',Rule::unique('users', 'email')->ignore($user),],
            'password' => ['required', 'confirmed', Password::defaults()],
            'company_name' => ['required'],
            'address' => ['required'],
            'start_time' => ['required', 'date_format:H:i:s'],
            'end_time' => ['required', 'date_format:H:i:s', 'after:start_time'],
            'break_time' => ['required', 'date_format:H:i'],
            'work_hours' => ['required', 'date_format:H:i'],
        ];

    }

    public function attributes()
    {
        return [
            'name' => '氏名',
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'password_confirmation' => 'パスワード（確認）',
            'company_name' => '会社名',
            'address' => '会社住所',
            'start_time' => '所定出勤時間',
            'end_time' => '所定退社時間',
            'break_time' => '所定休憩時間',
            'work_hours' => '所定労働時間',
        ];
    }

}
