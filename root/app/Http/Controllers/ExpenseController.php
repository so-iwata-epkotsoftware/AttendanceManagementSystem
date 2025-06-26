<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Models\Attendance;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     *経費申請画面
     */
    public function create()
    {
        return Inertia::render('VariousApplications/Expenses', [
            'user' => Auth::user(),
        ]);
    }

    /**
     *　経費申請処理
     */
    public function store(StoreExpenseRequest $request)
    {
        DB::transaction(function() use($request) {
            $user = Auth::user();
            $attendance_id = Attendance::where('date', $request->date)->value('id'); // ID値を取得

            $expense = Expense::create([
                'user_id' => $user->id,
                'company_id' => $user->company_id,
                'attendance_id' => $attendance_id,
                'expense_type' => $request->expense_type,
                'date' => $request->date,
                'section' => $request->section,
                'amount' => $request->amount,
                'note' => $request->note,
            ]);

            // ファイル取得
            $files = $request->file('receipt');

            foreach($files as $file) {
                $filename = $file->hashName(); // ファイル名生成
                $path = $file->storeAs('images/Expenses', $filename, 'public'); // 保存
                $type = $file->getClientOriginalExtension(); // 拡張子を取得

                // expense_receiptsリレーション経由で保存
                $expense->expenseReceipt()->create([
                    'file_path' => $path,
                    'file_type' => $type,
                ]);
            }
        });

        return to_route('attendances.expenses.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
