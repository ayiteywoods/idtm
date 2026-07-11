<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningMaterial extends Model
{
    public const UPLOAD_MIMES = 'pdf,doc,docx,ppt,pptx,xls,xlsx,zip';

    public const UPLOAD_MAX_KB = 20480; // 20 MB

    protected $fillable = [
        'course_id', 'faculty_profile_id', 'title', 'type',
        'description', 'url', 'file_path', 'original_name', 'is_published',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(FacultyProfile::class, 'faculty_profile_id');
    }
}
