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
        Schema::create('customer_satisfaction_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('rated_by')->constrained('users')->onDelete('cascade');
            $table->decimal('score', 3, 1); // Scale 1-5
            $table->string('period')->nullable(); // e.g., '2024-06', '2024-Q2'
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'period']);
            $table->index('rated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_satisfaction_scores');
    }
};
