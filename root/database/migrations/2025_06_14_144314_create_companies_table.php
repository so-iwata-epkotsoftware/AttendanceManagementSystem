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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->time('start_time')->default('09:00');; // 所定出社時間
            $table->time('end_time')->default('18:00'); // 所定退社時間
            $table->float('break_time')->default(1.0);
            $table->float('work_hours')->default(8.0); // 所定労働時間（例: 8.0 = 8時間、float型）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
