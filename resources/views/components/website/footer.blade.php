@props([
    'siteName',
    'footerLinks',
    'footerIntro',
    'contactEmail',
    'contactPhone',
    'contactAddress',
])

<footer class="website-footer">
    <div class="website-footer__main">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 lg:grid-cols-5 lg:px-8">
            <div class="lg:col-span-2">
                <x-website.logo variant="dark" />
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-brand-100">
                    {{ $footerIntro }}
                </p>
                <div class="mt-5 space-y-2 text-sm text-brand-100">
                    <p class="flex items-start gap-2">
                        <x-website.icon name="contact" class="mt-0.5 shrink-0 text-gold-400" />
                        <span>{{ $contactAddress }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        <x-website.icon name="mail" class="shrink-0 text-gold-400" />
                        <a href="mailto:{{ $contactEmail }}" class="hover:text-gold-300">{{ $contactEmail }}</a>
                    </p>
                    <p class="flex items-center gap-2">
                        <x-website.icon name="contact" class="shrink-0 text-gold-400" />
                        <span>
                            @foreach (array_filter(array_map('trim', explode(';', $contactPhone))) as $phone)
                                <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="hover:text-gold-300">{{ $phone }}</a>@if (! $loop->last)<span class="text-brand-200">; </span>@endif
                            @endforeach
                        </span>
                    </p>
                </div>
            </div>

            @foreach ($footerLinks as $heading => $links)
                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gold-300">{{ $heading }}</h3>
                    <ul class="mt-4 space-y-2 text-sm text-brand-100">
                        @foreach ($links as $link)
                            <li>
                                <a href="{{ $link['route'] }}" class="transition hover:text-gold-300">{{ $link['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <div class="website-footer__bottom">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-5 text-sm text-brand-200 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('contact') }}" class="hover:text-gold-300">Contact</a>
                <a href="{{ route('pages.show', 'terms') }}" class="hover:text-gold-300">Terms of Service</a>
                <a href="{{ route('pages.show', 'privacy') }}" class="hover:text-gold-300">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
