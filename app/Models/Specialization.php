<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialization extends Model
{
    protected $fillable = ['programme_id', 'name', 'order', 'required_courses'];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}
