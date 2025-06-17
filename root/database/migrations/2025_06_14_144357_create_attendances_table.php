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
            $table->dateTime('clock_in');
            $table->dateTime('clock_out');
            $table->float('work_hours');
            $table->float('overtime_hours');
            $table->integer('break_minutes')->default(0);
            $table->timestamps();
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
