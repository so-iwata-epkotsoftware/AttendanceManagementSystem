<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseReceipt extends Model
{
    protected $fillable = [
        'expense_id',
        'file_path',
        'file_type',
    ];

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }
}
