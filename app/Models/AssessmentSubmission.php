<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentSubmission extends Model
{
    protected $fillable = [
        'assessment_id', 'student_profile_id', 'file_path', 'original_name',
        'note', 'submitted_at', 'is_late', 'score', 'feedback', 'graded_by', 'graded_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'is_late' => 'boolean',
            'score' => 'decimal:2',
            'graded_at' => 'datetime',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'student_profile_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(FacultyProfile::class, 'graded_by');
    }

    public function isGraded(): bool
    {
        return $this->score !== null;
    }
}
