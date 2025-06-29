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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id(); // 経費ID（主キー）
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーID（外部キー）
            $table->foreignId('company_id')->constrained()->onDelete('cascade'); // 会社ID（外部キー）
            $table->foreignId('attendance_id')->nullable()->constrained()->onDelete('set null'); // 勤怠ID（任意）
            $table->enum('expense_type', ['transportation', 'travel', 'other']); // 経費の種類
            $table->date('date'); // 利用日
            $table->string('section', 255); // 利用区間・用途
            $table->integer('amount'); // 金額
            $table->text('note')->nullable(); // 備考・申請理由
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // ステータス
            $table->timestamps(); // created_at / updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
