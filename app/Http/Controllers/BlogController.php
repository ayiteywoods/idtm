<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('website.blog.index', compact('posts'));
    }

    public function show(BlogPost $post): View
    {
        abort_unless($post->is_published, 404);

        $recentPosts = BlogPost::query()
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('website.blog.show', compact('post', 'recentPosts'));
    }
}
