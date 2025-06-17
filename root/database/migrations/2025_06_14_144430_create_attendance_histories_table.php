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
        Schema::create('attendance_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 更新者のユーザーID
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade'); // attendanceテーブルの外部キー
            $table->string('field_changed'); // 変更されたフィールド名
            $table->text('old_value'); // 変更前の値
            $table->text('new_value'); // 変更後の値
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_histories');
    }
};
