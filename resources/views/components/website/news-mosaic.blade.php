@props(['cells'])

<section class="news-mosaic">
    <div class="news-mosaic__wrap mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="news-mosaic__header">
            <h2 class="news-mosaic__heading website-section-heading">Latest News &amp; Announcements</h2>
            <a href="{{ route('blog.index') }}" class="news-mosaic__see-all">See All News</a>
        </div>

        <div class="news-mosaic__grid">
            @foreach ($cells as $cell)
                @if ($cell['type'] === 'image')
                    <a href="{{ $cell['url'] }}" class="news-mosaic__cell news-mosaic__cell--{{ $cell['area'] }} news-mosaic__image">
                        <img src="{{ $cell['image'] }}" alt="{{ $cell['alt'] }}" loading="lazy">
                    </a>
                @elseif ($cell['type'] === 'featured')
                    <a href="{{ $cell['url'] }}" class="news-mosaic__cell news-mosaic__cell--{{ $cell['area'] }} news-mosaic__featured">
                        <img src="{{ $cell['image'] }}" alt="{{ $cell['alt'] }}" loading="lazy">
                        <div class="news-mosaic__featured-overlay">
                            <p class="news-mosaic__date">{{ $cell['date'] }}</p>
                            <h3 class="news-mosaic__title">{{ $cell['title'] }}</h3>
                            <p class="news-mosaic__excerpt">{{ $cell['excerpt'] }}</p>
                            <span class="news-mosaic__link">Read More →</span>
                        </div>
                    </a>
                @else
                    <article class="news-mosaic__cell news-mosaic__cell--{{ $cell['area'] }} news-mosaic__card news-mosaic__card--{{ $cell['variant'] }} {{ ! empty($cell['pointer']) ? 'news-mosaic__card--pointer-'.$cell['pointer'] : '' }}">
                        <p class="news-mosaic__date">{{ $cell['date'] }}</p>
                        <h3 class="news-mosaic__title">
                            <a href="{{ $cell['url'] }}">{{ $cell['title'] }}</a>
                        </h3>
                        <p class="news-mosaic__excerpt">{{ $cell['excerpt'] }}</p>
                        <a href="{{ $cell['url'] }}" class="news-mosaic__link">Read More →</a>
                    </article>
                @endif
            @endforeach
        </div>
    </div>
</section>
