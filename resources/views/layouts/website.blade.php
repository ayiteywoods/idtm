<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $siteName ?? config('app.name'))</title>
    <x-website.favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="website-header">
        <div class="website-header__anchor mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-website.logo crest class="website-header__crest" />
        </div>

        <div class="website-top-bar">
            <div class="website-header__inner mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="website-top-bar__row">
                    <div class="website-top-right">
                        <nav class="website-utility-nav" aria-label="Utility navigation">
                            @foreach ($utilityNav as $item)
                                <x-website.nav-item :item="$item" theme="utility" />
                            @endforeach
                        </nav>

                        <div class="website-top-actions">
                        <a href="{{ route('blog.index') }}" class="website-top-icon" aria-label="Search">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" class="website-top-icon" aria-label="Portal access">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="website-main-bar">
            <div class="website-header__inner mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="website-main-header">
                    <nav id="website-nav" class="website-main-nav" aria-label="Main navigation">
                        @foreach ($websiteNav as $item)
                            <x-website.nav-item :item="$item" />
                        @endforeach
                    </nav>

                    <div class="website-main-actions">
                        <a href="{{ route('pages.show', 'how-to-apply') }}" class="website-apply-btn">
                            <span>Apply Now</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                            </svg>
                        </a>

                        <button type="button" id="website-nav-toggle"
                                class="website-mobile-toggle"
                                aria-label="Toggle navigation"
                                aria-expanded="false"
                                aria-controls="website-nav-mobile">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <nav id="website-nav-mobile" class="website-nav-mobile hidden" aria-label="Mobile navigation">
                    <p class="website-nav-mobile__label">Main Menu</p>
                    <div class="website-nav-mobile__group">
                        @foreach ($websiteNav as $item)
                            @if (($item['type'] ?? 'link') === 'dropdown')
                                <div class="website-mobile-nav-group" data-mobile-nav-group>
                                    <button type="button" class="website-mobile-nav-group__trigger">
                                        <span>{{ $item['label'] }}</span>
                                        <svg class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <div class="website-mobile-nav-group__panel hidden">
                                        @foreach ($item['items'] as $subItem)
                                            <a href="{{ $subItem['route'] }}">{{ $subItem['label'] }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $item['route'] }}" class="website-nav-mobile__link">{{ $item['label'] }}</a>
                            @endif
                        @endforeach
                    </div>

                    <p class="website-nav-mobile__label">Quick Links</p>
                    <div class="website-nav-mobile__group">
                        @foreach ($utilityNav as $item)
                            @if (($item['type'] ?? 'link') === 'dropdown')
                                <div class="website-mobile-nav-group" data-mobile-nav-group>
                                    <button type="button" class="website-mobile-nav-group__trigger">
                                        <span>{{ $item['label'] }}</span>
                                        <svg class="h-4 w-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <div class="website-mobile-nav-group__panel hidden">
                                        @foreach ($item['items'] as $subItem)
                                            <a href="{{ $subItem['route'] }}">{{ $subItem['label'] }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $item['route'] }}" class="website-nav-mobile__link">{{ $item['label'] }}</a>
                            @endif
                        @endforeach
                    </div>

                    <a href="{{ route('pages.show', 'how-to-apply') }}" class="website-apply-btn website-apply-btn--mobile">
                        <span>Apply Now</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                        </svg>
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <x-website.footer
        :site-name="$siteName"
        :footer-links="$footerLinks"
        :footer-intro="$footerIntro"
        :contact-email="$contactEmail"
        :contact-phone="$contactPhone"
        :contact-address="$contactAddress"
    />
</body>
</html>
