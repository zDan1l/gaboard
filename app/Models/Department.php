<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[\Illuminate\Database\Eloquent\Attributes\Fillable(['name', 'code', 'location', 'description'])]
class Department extends Model
{
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
