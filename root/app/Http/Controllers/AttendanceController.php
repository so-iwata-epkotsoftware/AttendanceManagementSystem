<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Company;
use App\Models\User;
use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $attendances = Attendance::select('id', 'user_id', 'company_id', 'clock_in', 'clock_out', 'work_hours', 'break_minutes', 'overtime_hours')->where('user_id', $user->id)
            ->whereBetween('clock_in', [$startDate, $endDate])
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
            $data[Carbon::parse($attendance->clock_in)->format('Y/m/d')] = [$attendance];
        }

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

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('clock_in', $today)
            ->first();

        return Inertia::render('Attendances/Create', [
            'user' => $user,
            'stampData' =>  $attendance,
        ]);
    }

    /**
     * 打刻登録処理
     */
    public function store(StoreAttendanceRequest $request)
    {
        // dd($request);

        DB::transaction(function() use($request) {
            $clockIn = Carbon::parse($request['date'] . $request['clock_in']);
            $clockOut = Carbon::parse($request['date'] . $request['clock_out']);
            $breakMinutes = intval($request['break_minutes']);
            $DesignatedWorkingHours = Company::where('id', $request['company_id'])->value('work_hours');

            // 差分（分単位）で取得
            $totalMinutes = $clockIn->diffInMinutes($clockOut);

            // 実働時間（分単位 → 時間に変換）
            $workHours = ($totalMinutes - $breakMinutes) / 60;
            // 残業時間
            $overtime_hours = $workHours - $DesignatedWorkingHours;

            $attendance = Attendance::create([
                'user_id' => $request->user_id,
                'company_id' => $request->company_id,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'break_minutes' => $request['break_minutes'],
                'work_hours' => $workHours,
                'overtime_hours' => $overtime_hours,
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
            $clockIn = Carbon::parse($request['date'] . $request['clock_in']);
            $clockOut = Carbon::parse($request['date'] . $request['clock_out']);
            $breakMinutes = intval($request['break_minutes']);
            $DesignatedWorkingHours = $attendance->company->work_hours;

            // 差分（分単位）で取得
            $totalMinutes = $clockIn->diffInMinutes($clockOut);

            // 実働時間（分単位 → 時間に変換）
            $workHours = ($totalMinutes - $breakMinutes) / 60;
            // 残業時間
            $overtime_hours = $workHours - $DesignatedWorkingHours;

            $attendance->update([
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'break_minutes' => $request['break_minutes'],
                'work_hours' => $workHours,
                'overtime_hours' => $overtime_hours,
            ]);


            $attendance->vacation->update([
                'vacation_type' => $request['vacation_type'],
            ]);

            $attendance->attendance_status->update([
                'reason' => $request['reason'],
            ]);
        });

        return to_route('attendances.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
