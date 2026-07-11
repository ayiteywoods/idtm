<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    public const TYPES = ['assignment', 'exam', 'project', 'quiz'];

    public const UPLOAD_MIMES = 'pdf,doc,docx,ppt,pptx,xls,xlsx,zip';

    public const UPLOAD_MAX_KB = 20480; // 20 MB

    protected $fillable = [
        'course_id', 'faculty_profile_id', 'type', 'title', 'instructions',
        'max_score', 'due_at', 'attachment_path', 'attachment_name',
        'allow_submissions', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'max_score' => 'decimal:2',
            'due_at' => 'datetime',
            'allow_submissions' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(FacultyProfile::class, 'faculty_profile_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssessmentSubmission::class);
    }

    public function isPastDue(): bool
    {
        return $this->due_at !== null && $this->due_at->isPast();
    }
}
