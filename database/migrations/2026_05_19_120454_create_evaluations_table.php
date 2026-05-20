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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->string('evaluation_period', 50); // e.g., "Q1 2026", "Januari 2026"

            // Input variables for Fuzzy Logic
            $table->decimal('kpi_score', 5, 2)->default(0); // 0-100
            $table->decimal('attendance_rate', 5, 2)->default(0); // 0-100
            $table->decimal('customer_satisfaction', 3, 1)->default(0); // 1-10

            // Calculated results from Fuzzy Logic
            $table->decimal('fuzzy_score', 3, 2)->default(0); // 0.00-1.00
            $table->enum('category', ['sangat_baik', 'baik', 'cukup', 'buruk', 'sangat_buruk'])->default('buruk');
            $table->text('hr_recommendation')->nullable();

            // Additional metadata
            $table->text('notes')->nullable();
            $table->timestamps();

            // Index for faster queries
            $table->index('evaluation_period');
            $table->index('fuzzy_score');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
