<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Company;
use App\Models\Expense;
use App\Models\User;
use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * 勤怠一覧
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $date = [
            'year' => $request->year ?? date('Y'),
            'month' => $request->month ?? date('m'),
        ];

        $startDate = Carbon::create($date['year'], $date['month'], 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();


        $attendances = Attendance::select('id', 'date', 'user_id', 'company_id', 'clock_in', 'clock_out', 'work_hours', 'break_minutes', 'overtime_hours', 'created_at')->where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('clock_in', 'asc')
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

        // dd($data);

        return Inertia::render('Attendances/Index', [
            'data' => $data,
            'user' => $user,
            'date' => $date,
        ]);
    }

    /**
     * 打刻登録画面
     */
    public function create()
    {
        $user = Auth::user();

        $today = Carbon::today();

        $attendance = Attendance::select('id', 'user_id', 'company_id', 'clock_in', 'clock_out', 'break_minutes')
            ->where('user_id', $user->id)
            ->whereDate('date', $today)
            ->with([
                'vacation' => function($query) {
                    $query->select('id', 'user_id', 'attendance_id', 'vacation_type');
                },
                'attendance_status' => function($query) {
                    $query->select('id', 'user_id', 'attendance_id', 'reason', 'status');
                },
            ])
            ->first();

            if ($attendance['clock_in'] ?? null)
            {
                $attendance['clock_in'] = Carbon::parse($attendance['clock_in'])->format('H:i');
            }

        return Inertia::render('Attendances/Create', [
            'today' => $today->format('Y/m/d'),
            'user' => $user,
            'stampData' =>  $attendance,
        ]);
    }

    /**
     * 打刻登録処理
     */
    public function store(StoreAttendanceRequest $request)
    {

        // dd(Carbon::parse($request->date)->format('Y-m-d'));

        DB::transaction(function() use($request) {
            $clockIn = $request['clock_in'] ?
                        Carbon::parse($request['date'] . ' ' . $request['clock_in'])
                        : null;
            $clockOut = $request['clock_out'] ?
                        Carbon::parse($request['date'] . ' ' . $request['clock_out'])
                        : null;

            if ($request['clock_in'] && $request['clock_out']) {
                $breakMinutes = intval($request['break_minutes']);
                $DesignatedWorkingHours = Company::where('id', $request['company_id'])->value('work_hours');

                // 差分（分単位）で取得
                $totalMinutes = $clockIn->diffInMinutes($clockOut);

                // 実働時間（分単位 → 時間に変換）
                $workHours = ($totalMinutes - $breakMinutes) / 60;
                // 残業時間
                $overtime_hours = $workHours - $DesignatedWorkingHours;
            }

            $attendance = Attendance::create([
                'date' => Carbon::parse($request->date)->format('Y-m-d'),
                'user_id' => $request->user_id,
                'company_id' => $request->company_id,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'break_minutes' => $request['break_minutes'],
                'work_hours' => $workHours ?? null,
                'overtime_hours' => $overtime_hours ?? null,
            ]);

            Vacation::create([
                'user_id' => $request->user_id,
                'attendance_id' => $attendance->id,
                'vacation_type' => $request['vacation_type'],
            ]);

            AttendanceStatus::create([
                'user_id' => $request->user_id,
                'attendance_id' => $attendance->id,
                'reason' => $request['reason'],
            ]);
        });

        return to_route('attendances.index');
    }

    /**
     * 勤怠編集処理
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        DB::transaction(function() use($request, $attendance) {
            $clockIn = $request['clock_in'] ?
                        Carbon::parse($request['date'] . ' ' . $request['clock_in'])
                        : null;
            $clockOut = $request['clock_out'] ?
                        Carbon::parse($request['date'] . ' ' . $request['clock_out'])
                        : null;
            $breakMinutes = intval($request['break_minutes']);
            $DesignatedWorkingHours = $attendance->company->work_hours;

            if ($request['clock_in'] && $request['clock_out']) {
                $breakMinutes = intval($request['break_minutes']);
                $DesignatedWorkingHours = Company::where('id', $request['company_id'])->value('work_hours');

                // 差分（分単位）で取得
                $totalMinutes = $clockIn->diffInMinutes($clockOut);

                // 実働時間（分単位 → 時間に変換）
                $workHours = ($totalMinutes - $breakMinutes) / 60;
                // 残業時間
                $overtime_hours = $workHours - $DesignatedWorkingHours;
            }

            $attendance->update([
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'break_minutes' => $request['break_minutes'],
                'work_hours' => $workHours ?? null,
                'overtime_hours' => $overtime_hours ?? null,
            ]);


            $attendance->vacation->update([
                'vacation_type' => $request['vacation_type'],
            ]);

            $attendance->attendance_status->update([
                'reason' => $request['reason'],
                'status' => 'pending',
            ]);
        });

        return to_route('attendances.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
    }

    public function request_attendances()
    {
        return Inertia::render('VariousApplications/RequestAttendances', [
            'user' => Auth::user(),
        ]);
    }
}
