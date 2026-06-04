<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiReport extends Model
{
    protected $fillable = [
        'kpi_target_id',
        'reported_by',
        'actual_value',
        'notes',
        'report_date',
    ];

    protected $casts = [
        'actual_value' => 'decimal:2',
        'report_date' => 'date',
    ];

    public function kpiTarget(): BelongsTo
    {
        return $this->belongsTo(KpiTarget::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function getAchievementPercentageAttribute(): float
    {
        if (! $this->kpiTarget || $this->kpiTarget->target_value == 0) {
            return 0;
        }

        return ($this->actual_value / $this->kpiTarget->target_value) * 100;
    }
}
