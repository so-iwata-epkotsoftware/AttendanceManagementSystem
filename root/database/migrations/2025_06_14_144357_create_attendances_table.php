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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // usersテーブルの外部キー
            $table->foreignId('company_id')->constrained()->onDelete('cascade'); // companiesテーブルの外部キー
            $table->date('date');
            $table->dateTime('clock_in')->nullable();
            $table->dateTime('clock_out')->nullable();
            $table->float('work_hours')->nullable();
            $table->float('overtime_hours')->nullable();
            $table->integer('break_minutes')->nullable()->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'date']); // 同じユーザーが同じ日に複数レコードを登録出来ないよう設定
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
