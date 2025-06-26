<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'attendance_id',
        'expense_type',
        'date',
        'section',
        'amount',
        'note',
        'status',
    ];

    protected $appends = [
        'expense_type_jp',
        'status_jp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(company::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function expenseReceipt()
    {
        return $this->hasOne(ExpenseReceipt::class);
    }

    protected function expenseTypeJp(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->expense_type)
                        {
                            'transportation' => '交通費',
                            'travel' => '出張費',
                            'other' => 'その他',
                        }
        );
    }

    protected function statusJp(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->status)
                        {
                            'pending' => '未承認',
                            'approved' => '承認',
                            'rejected' => '否認',
                        }
        );
    }
}
