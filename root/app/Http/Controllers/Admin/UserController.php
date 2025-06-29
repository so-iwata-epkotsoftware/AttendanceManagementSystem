<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RegisterUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Admin;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * ユーザー一覧表示画面
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        $staff_users = User::searchUser($request->searchText)
            ->searchCompany($request->selectedCompanies)
            ->select('id', 'name', 'company_id', 'email')
            ->where('admin_id', $admin->id)
            ->with('company')
            ->withCount([
                'attendance_status as pending_count' => function ($query) {
                    $query->where('status', 'pending');
                },
                'attendance_status as rejected_count' => function ($query) {
                    $query->where('status', 'rejected');
                },
            ])
            ->paginate(10);

        $companies = [];

        $companies = User::where('admin_id', $admin->id)
            ->with('company:id,name')->get()
            ->pluck('company')->pluck('name')->unique()->values();

        return Inertia::render('Admin/User/Index', [
            'user' => $admin,
            'staff_users' => $staff_users,
            'companies' => $companies,
        ]);
    }

    protected function decimal(String $time)
    {
        [$hour, $minute] = explode(':', $time);
        return (int)$hour + ((int)$minute / 60);
    }

    /**
     * ユーザー新規登録処理
     */
    public function store(RegisterUserRequest $request)
    {
        DB::transaction(function() use($request) {
            $break_time = $this->decimal($request->break_time);
            $work_hours = $this->decimal($request->work_hours);

            $company = Company::create([
                'name' => $request->company_name,
                'address' => $request->address,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'break_time' => $break_time,
                'work_hours' => $work_hours,
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'admin_id' => Auth::id(),
                'company_id' => $company->id,
            ]);
        });

        return to_route('admin.user.index');
    }

    /**
     * ユーザー情報更新
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        DB::transaction(function() use($request, $user) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            $user->company()->update([
                'name' => $request->company_name,
                'address' => $request->address,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'break_time' => $this->decimal($request->break_time),
                'work_hours' => $this->decimal($request->work_hours),
            ]);
        });

        return to_route('admin.user.index');
    }

    /**
     * ユーザー削除処理
     */
    public function destroy(Request $request)
    {
        // dd(array_column($request->users, 'id'));
        if ($request->users) {
            User::whereIn('id', array_column($request->users, 'id'))->delete();
        }

        return to_route('admin.user.index');
    }

    public function dashboard()
    {
        $request_users = User::select('id', 'name', 'company_id')
            ->withCount([
                'attendance_status as pending_count' => function ($query) {
                    $query->where('status', 'pending');
                },
                'attendance_status as rejected_count' => function ($query) {
                    $query->where('status', 'rejected');
                },
                'company',
            ])
            ->paginate(10);

        return Inertia::render('Admin/Dashboard', [
            'user' => Auth::user(),
            'request_users' => $request_users,
        ]);
    }
}
