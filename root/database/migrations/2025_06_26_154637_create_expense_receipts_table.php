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
        Schema::create('expense_receipts', function (Blueprint $table) {
            $table->id(); // 領収書ID（主キー）
            $table->foreignId('expense_id')->constrained('expenses')->onDelete('cascade'); // 経費ID（外部キー）
            $table->string('file_path', 255); // ファイルパス
            $table->string('file_type', 50); // ファイル種別（png, pdf等）
            $table->timestamps(); // created_at / updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_receipts');
    }
};
