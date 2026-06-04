<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSchedule extends Model
{
    protected $fillable = [
        'created_by',
        'schedule_date',
        'title',
        'description',
        'is_working_day',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'is_working_day' => 'boolean',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(AttendanceEntry::class, 'schedule_id');
    }

    public function getPresentCountAttribute(): int
    {
        return $this->entries()->where('status', 'present')->count();
    }

    public function getAbsentCountAttribute(): int
    {
        return $this->entries()->where('status', 'absent')->count();
    }
}
