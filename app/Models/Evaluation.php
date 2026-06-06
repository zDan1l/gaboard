<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    protected $fillable = [
        'employee_id',
        'evaluator_id',
        'evaluation_period',
        'start_date',
        'end_date',
        'kpi_score',
        'attendance_rate',
        'customer_satisfaction',
        'fuzzy_score',
        'category',
        'hr_recommendation',
        'notes',
        'fuzzification_details',
    ];

    protected $casts = [
        'kpi_score' => 'decimal:2',
        'attendance_rate' => 'decimal:2',
        'customer_satisfaction' => 'decimal:1',
        'fuzzy_score' => 'decimal:2',
        'fuzzification_details' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'sangat_baik' => 'Sangat Baik',
            'baik' => 'Baik',
            'cukup' => 'Cukup',
            'buruk' => 'Buruk',
            'sangat_buruk' => 'Sangat Buruk',
            default => 'Unknown',
        };
    }

    public function getPerformanceClassAttribute(): string
    {
        return match ($this->category) {
            'sangat_baik' => 'success',
            'baik' => 'primary',
            'cukup' => 'warning',
            'buruk' => 'danger',
            'sangat_buruk' => 'dark',
            default => 'secondary',
        };
    }
}
