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
        Schema::table('evaluations', function (Blueprint $table) {
            // Add unique constraint to prevent duplicate evaluations for same employee and period
            $table->unique(['employee_id', 'evaluation_period'], 'evaluations_employee_period_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique('evaluations_employee_period_unique');
        });
    }
};
