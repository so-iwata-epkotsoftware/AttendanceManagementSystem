<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade'); // attendanceテーブルの外部キー
            $table->enum('status', ['pending', 'approved', 'rejected']);
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 更新者のユーザーID
            $table->text('reason')->nullable(); // ステータス変更理由
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_statuses');
    }
};
