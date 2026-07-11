<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    protected $fillable = ['slug', 'title', 'eyebrow', 'subtitle', 'content', 'blocks', 'meta_description', 'is_published'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'blocks' => 'array',
        ];
    }
}
