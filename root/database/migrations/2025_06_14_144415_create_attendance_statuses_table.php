<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const STATUS = [
        'pending', # 未承認
        'approved', # 承認
        'rejected', # 否認
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 更新者のユーザーID
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade'); // attendanceテーブルの外部キー
            $table->enum('status', self::STATUS);
            $table->text('reason')->nullable(); // ステータス変更
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
