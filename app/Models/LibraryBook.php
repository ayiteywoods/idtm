<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryBook extends Model
{
    public const UPLOAD_MIMES = 'pdf,doc,docx,ppt,pptx,xls,xlsx,zip,epub';

    public const UPLOAD_MAX_KB = 51200; // 50 MB

    protected $fillable = [
        'uploaded_by', 'title', 'author', 'description',
        'cover_image', 'file_path', 'original_name', 'external_url', 'is_published',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
