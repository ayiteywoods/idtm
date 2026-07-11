<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programme extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'total_fees', 'is_active'];

    protected function casts(): array
    {
        return ['total_fees' => 'decimal:2', 'is_active' => 'boolean'];
    }

    public function specializations(): HasMany
    {
        return $this->hasMany(Specialization::class)->orderBy('order');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(StudentProfile::class);
    }
}
