<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceStatusRequest;
use App\Http\Requests\UpdateAttendanceStatusRequest;
use App\Models\AttendanceStatus;

class AttendanceStatusController extends Controller
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
    public function store(StoreAttendanceStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceStatus $attendanceStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceStatus $attendanceStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceStatusRequest $request, AttendanceStatus $attendanceStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceStatus $attendanceStatus)
    {
        //
    }
}
