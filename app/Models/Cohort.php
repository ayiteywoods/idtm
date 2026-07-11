<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cohort extends Model
{
    protected $fillable = ['name', 'start_date', 'is_active'];

    protected function casts(): array
    {
        return ['start_date' => 'date', 'is_active' => 'boolean'];
    }

    public function students(): HasMany
    {
        return $this->hasMany(StudentProfile::class);
    }
}
