@props(['post'])

<article class="website-blog-card">
    <a href="{{ route('blog.show', $post) }}" class="website-blog-card__image-wrap">
        @if ($post->coverImageUrl())
            <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}" class="website-blog-card__image">
        @else
            <div class="website-blog-card__placeholder">
                <span>{{ strtoupper(substr($post->category ?? 'IDTM', 0, 3)) }}</span>
            </div>
        @endif
        @if ($post->category)
            <span class="website-blog-card__category">{{ $post->category }}</span>
        @endif
    </a>
    <div class="website-blog-card__body">
        <p class="website-blog-card__date">{{ $post->published_at?->format('M j, Y') }}</p>
        <h3 class="website-blog-card__title">
            <a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a>
        </h3>
        <p class="website-blog-card__excerpt">{{ $post->excerpt }}</p>
        <a href="{{ route('blog.show', $post) }}" class="website-blog-card__link">Read more →</a>
    </div>
</article>
