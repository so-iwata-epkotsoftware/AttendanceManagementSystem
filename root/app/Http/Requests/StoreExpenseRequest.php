<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
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
        return [
            'expense_type' => ['required', 'in:transportation,travel,other'],
            'date'         => ['required', 'date'],
            'section'      => ['required', 'string', 'max:255'],
            'amount'       => ['required', 'integer', 'min:0'],
            'receipt'      => ['required', 'array'],
            'receipt[*]'    => ['file', 'image', 'mimes:jpeg,png,jpg,gif,svg,pdf', 'max:2048'],
            'note'         => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes()
    {
        return [
            'expense_type' => '経費の種類',
            'date'         => '利用日',
            'section'      => '利用区間',
            'amount'       => '金額',
            'receipt'    => '画像',
            'note'         => '備考・申請理由',
        ];
    }
}
