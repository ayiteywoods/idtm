<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentPlan extends Model
{
    protected $fillable = ['student_profile_id', 'total_fees', 'total_deposited', 'currency'];

    protected function casts(): array
    {
        return ['total_fees' => 'decimal:2', 'total_deposited' => 'decimal:2'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function installments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class);
    }

    public function outstanding(): float
    {
        return (float) $this->total_fees - (float) $this->total_deposited;
    }
}
