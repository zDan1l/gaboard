<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'department_id', 'manager_id', 'employee_code', 'position', 'phone', 'join_date', 'status'])]
class Employee extends Model
{
    protected function casts(): array
    {
        return [
            'join_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get employees that report to this employee (through their manager user).
     * Note: Since regular employees don't directly manage others in this design,
     * this relationship is for when we extend the hierarchy in the future.
     */
    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    /**
     * Get HR Managers (users with hr_manager role) who can manage employees.
     * This is a helper to get all potential managers.
     */
    public static function getManagers()
    {
        return User::whereHas('role', function ($query) {
            $query->where('slug', 'hr_manager');
        })->get();
    }

    public function kpiTargets(): HasMany
    {
        return $this->hasMany(KpiTarget::class);
    }

    public function attendanceEntries(): HasMany
    {
        return $this->hasMany(AttendanceEntry::class);
    }

    public function customerSatisfactionScores(): HasMany
    {
        return $this->hasMany(CustomerSatisfactionScore::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function getAttendanceRateAttribute(): float
    {
        $totalEntries = $this->attendanceEntries()->count();
        if ($totalEntries === 0) {
            return 0;
        }

        $present = $this->attendanceEntries()
            ->whereIn('status', ['present', 'late'])
            ->count();

        return ($present / $totalEntries) * 100;
    }

    public function getAverageCustomerSatisfactionAttribute(): ?float
    {
        return $this->customerSatisfactionScores()
            ->avg('score');
    }
}
