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
            // Add new date range columns
            $table->date('start_date')->after('id')->nullable();
            $table->date('end_date')->after('start_date')->nullable();

            // Make evaluation_period nullable for backward compatibility
            $table->string('evaluation_period', 50)->nullable()->change();
        });

        // Migrate existing data - convert period strings to date ranges
        // Example: "2026-06" -> start: 2026-06-01, end: 2026-06-30
        DB::statement("
            UPDATE evaluations
            SET
                start_date = STR_TO_DATE(CONCAT(SUBSTRING_INDEX(evaluation_period, '-', 1), '-', SUBSTRING_INDEX(evaluation_period, '-', -1), '-01'), '%Y-%m-%d'),
                end_date = LAST_DAY(STR_TO_DATE(CONCAT(SUBSTRING_INDEX(evaluation_period, '-', 1), '-', SUBSTRING_INDEX(evaluation_period, '-', -1), '-01'), '%Y-%m-%d'))
            WHERE evaluation_period REGEXP '^[0-9]{4}-[0-9]{2}$'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
            $table->string('evaluation_period', 50)->nullable(false)->change();
        });
    }
};
