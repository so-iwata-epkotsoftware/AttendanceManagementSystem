<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VariousApplicationsController extends Controller
{
    /**
     * 各種申請一覧
     */
    public function index()
    {
        $admin = Auth::user();

        $expenses = Expense::with([
                'user:id,name,email',
                'expenseReceipts'
            ])
            ->whereHas('user', function($query) use($admin) {
                $query->where('admin_id', $admin->id);
            })
            ->get();

        return Inertia::render('Admin/VariousApplications/Index', [
            'user' => $admin,
            'expenses' => $expenses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, Expense $expense)
    {
        $expense->update([
            'status' => $request->status,
            'note' => $request->note,
        ]);

        return to_route('admin.variousApplications.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
