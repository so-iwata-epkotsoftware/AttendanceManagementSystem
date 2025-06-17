<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const ATTENDANCE_CLASSIFICATION = [
        'RegularAttendance', # 通常出勤
        'LateArrival', # 遅刻
        'EarlyLeaving', # 早退
        'Absence', # 欠勤
        'PaidLeave', # 有給休暇
        'SpecialLeave', # 特別休暇
        'HolidayWork', # 休日出勤
        'CompensatedLeave', # 振替出勤
        'StaggeredWork', # 時差出勤	　
        'Telecommuting', # 在宅勤務
        'BusinessTravel', # 出張
        'HalfDayPaidMorning', # 半日有給（午前）
        'HalfDayPaidAfternoon', # 半日有給（午後）
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vacations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // usersテーブルの外部キー
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade'); // attendanceテーブルの外部キー
            $table->enum('vacation_type', self::ATTENDANCE_CLASSIFICATION); // 休暇タイプ
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacations');
    }
};
