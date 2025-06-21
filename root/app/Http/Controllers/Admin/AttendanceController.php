<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAttendanceRequest;
use App\Http\Requests\Admin\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Company;
use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreAttendanceRequest $request)
    {
        // dd($request);

        DB::transaction(function() use($request) {
            $clockIn = Carbon::parse($request['date'] . ' ' . $request['clock_in']);
            $clockOut = $request['clock_out'] ?
                        Carbon::parse($request['date'] . ' ' . $request['clock_out'])
                        : null;

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

        return to_route('admin.user.index');
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
     * ユーザー打刻承認処理.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        // dd($request, $attendance);

        DB::transaction(function() use($request, $attendance) {
            $clockIn = Carbon::parse($request['date'] . ' ' . $request['clock_in']);
            $clockOut = $request['clock_out'] ?
                        Carbon::parse($request['date'] . ' ' . $request['clock_out'])
                        : null;
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
                'status' => $request['status'],
            ]);

            $attendance->attendance_status->update([
                    'reason' => $request['reason'],
                    'status' => $request['status'],
                ]);
        });

        return to_route('admin.user.show', $request->user_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
