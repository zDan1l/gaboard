<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KpiTarget extends Model
{
    protected $fillable = [
        'employee_id',
        'created_by',
        'title',
        'description',
        'target_value',
        'unit',
        'period',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(KpiReport::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getAchievementPercentageAttribute(): float
    {
        $latestReport = $this->reports()->latest()->first();

        if (! $latestReport || $this->target_value == 0) {
            return 0;
        }

        return ($latestReport->actual_value / $this->target_value) * 100;
    }
}
