<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerSatisfactionScore extends Model
{
    protected $fillable = [
        'employee_id',
        'rated_by',
        'score',
        'period',
        'notes',
    ];

    protected $casts = [
        'score' => 'decimal:1',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function ratedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_by');
    }

    public function getScoreLabelAttribute(): string
    {
        return match (true) {
            $this->score >= 4.5 => 'Sangat Baik',
            $this->score >= 3.5 => 'Baik',
            $this->score >= 2.5 => 'Cukup',
            $this->score >= 1.5 => 'Kurang',
            default => 'Sangat Kurang',
        };
    }
}
