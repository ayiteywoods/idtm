<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id', 'programme_id', 'cohort_id',
        'first_specialization_id', 'second_specialization_id',
        'index_number', 'first_name', 'other_names', 'last_name',
        'gender', 'date_of_birth', 'phone', 'country', 'location',
        'region', 'religion', 'profile_photo',
    ];

    protected function casts(): array
    {
        return ['date_of_birth' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    public function cohort(): BelongsTo
    {
        return $this->belongsTo(Cohort::class);
    }

    public function firstSpecialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class, 'first_specialization_id');
    }

    public function secondSpecialization(): BelongsTo
    {
        return $this->belongsTo(Specialization::class, 'second_specialization_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CourseRegistration::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function changeRequests(): HasMany
    {
        return $this->hasMany(ChangeRequest::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssessmentSubmission::class);
    }

    public function paymentPlan(): HasOne
    {
        return $this->hasOne(PaymentPlan::class);
    }

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->other_names} {$this->last_name}");
    }

    public function profilePhotoUrl(): ?string
    {
        if (blank($this->profile_photo)) {
            return null;
        }

        if (filter_var($this->profile_photo, FILTER_VALIDATE_URL)) {
            return $this->profile_photo;
        }

        if (str_starts_with($this->profile_photo, '/')) {
            return $this->profile_photo;
        }

        return '/storage/'.$this->profile_photo;
    }
}
