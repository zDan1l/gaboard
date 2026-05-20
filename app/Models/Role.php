<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[\Illuminate\Database\Eloquent\Attributes\Fillable(['name', 'slug', 'description'])]
class Role extends Model
{
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
