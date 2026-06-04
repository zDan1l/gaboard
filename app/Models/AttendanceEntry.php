<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceEntry extends Model
{
    protected $fillable = [
        'schedule_id',
        'employee_id',
        'clock_in_time',
        'clock_out_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(AttendanceSchedule::class, 'schedule_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getWorkingHoursAttribute(): ?float
    {
        if (! $this->clock_in_time || ! $this->clock_out_time) {
            return null;
        }

        return $this->clock_in_time->diffInHours($this->clock_out_time);
    }
}
