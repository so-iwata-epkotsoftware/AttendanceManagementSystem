<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RegisterUserRequest;
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
        $staff_users = User::searchUser($request->searchUser)
            ->searchCompany($request->selectedCompanies)
            ->select('id', 'name', 'company_id')
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

    /**
     * ユーザー新規登録画面
     */
    public function create()
    {
        return Inertia::render('Admin/User/Create', [
            'user' => Auth::user(),
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
     * ユーザー詳細画面
     */
    public function show(Request $request,User $user)
    {
        $admin = Auth::user();

        $date = [
            'year' => $request->year ?? date('Y'),
            'month' => $request->month ?? date('m'),
        ];

        $startDate = Carbon::create($date['year'], $date['month'], 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $attendances = Attendance::select('id', 'date', 'user_id', 'company_id', 'clock_in', 'clock_out', 'work_hours', 'break_minutes', 'overtime_hours')->where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->with(['attendance_status' => function($query) {
                        $query->select('id', 'user_id', 'attendance_id', 'status', 'reason');
                    },
                    'vacation' => function($query) {
                        $query->select('id', 'attendance_id', 'vacation_type');
                    }])
            ->get();

        $data = [];
        for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
            $data[$d->format('Y/m/d')] = [
                'user_id' => $user->id,
                'company_id' => $user->company_id,
            ];
        }

        foreach ($attendances as $attendance)
        {
            $data[Carbon::parse($attendance->date)->format('Y/m/d')] = [$attendance];
        }


        return Inertia::render('Admin/User/Show', [
            'user' => $admin,
            'data' => $data,
            'date' => $date,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     *
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
