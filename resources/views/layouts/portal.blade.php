<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    <x-website.favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    @php
        $portalType = $portalNav['type'];
        $userMenuItems = match ($portalType) {
            'admin' => [
                ['label' => 'Dashboard', 'route' => route('admin.dashboard'), 'icon' => 'dashboard'],
                ['label' => 'Site Settings', 'route' => route('admin.settings.index'), 'icon' => 'settings'],
                ['label' => 'Website CMS', 'route' => route('admin.website.index'), 'icon' => 'globe'],
                ['label' => 'Manage Students', 'route' => route('admin.students.index'), 'icon' => 'users'],
                ['label' => 'Manage Faculty', 'route' => route('admin.faculty.index'), 'icon' => 'academic'],
            ],
            'faculty' => [
                ['label' => 'My Dashboard', 'route' => route('faculty.dashboard'), 'icon' => 'dashboard'],
                ['label' => 'My Courses', 'route' => route('faculty.courses.index'), 'icon' => 'book'],
                ['label' => 'Learning Materials', 'route' => route('faculty.materials.index'), 'icon' => 'folder'],
            ],
            default => [
                ['label' => 'My Dashboard', 'route' => route('student.dashboard'), 'icon' => 'dashboard'],
                ['label' => 'My Profile', 'route' => route('student.profile'), 'icon' => 'user'],
                ['label' => 'My Wallet', 'route' => route('student.wallet'), 'icon' => 'wallet'],
            ],
        };
    @endphp

    <div class="flex h-screen overflow-hidden">
        <div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-brand-900/50 lg:hidden" aria-hidden="true"></div>

        <aside id="sidebar"
               class="sidebar-panel fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-brand-900 text-white transition-all duration-200 lg:static lg:translate-x-0 lg:w-72">
            <div class="flex h-16 items-center gap-3 border-b border-brand-800 px-4 lg:px-5">
                <x-website.logo size="sm" mark-only class="sidebar-logo shrink-0" />
                <div class="sidebar-text min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold">{{ $portalNav['name'] }}</p>
                    <p class="truncate text-xs text-gold-300">{{ $portalNav['subtitle'] }}</p>
                </div>
                <button type="button" id="sidebar-collapse-btn"
                        class="hidden shrink-0 rounded-lg p-1.5 text-brand-200 hover:bg-brand-800 hover:text-gold-300 lg:inline-flex"
                        aria-label="Collapse sidebar">
                    <svg class="sidebar-collapse-icon h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto overflow-x-hidden px-3 py-4">
                @foreach ($portalNav['sections'] as $section)
                    <div class="sidebar-section mb-5">
                        <p class="sidebar-text mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-gold-400">{{ $section['label'] }}</p>
                        <ul class="space-y-0.5">
                            @foreach ($section['items'] as $item)
                                @php $active = request()->routeIs($item['active']); @endphp
                                <li>
                                    <a href="{{ $item['route'] }}"
                                       title="{{ $item['label'] }}"
                                       class="sidebar-nav-link group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                                       {{ $active ? 'bg-gold-500 text-brand-900 shadow-md shadow-black/20' : 'text-brand-100 hover:bg-brand-800 hover:text-gold-100' }}">
                                        <x-portal.icon :name="$item['icon']" class="h-5 w-5 shrink-0 {{ $active ? 'text-brand-900' : 'text-brand-300 group-hover:text-gold-200' }}" />
                                        <span class="sidebar-text truncate">{{ $item['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </nav>

            <div class="sidebar-footer border-t border-brand-800 p-4">
                <div class="flex items-center gap-3 rounded-lg bg-brand-800/60 p-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold-500 text-sm font-bold text-brand-900">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="sidebar-text min-w-0 flex-1">
                        <p class="truncate text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs capitalize text-brand-300">{{ auth()->user()->role->value }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <header class="flex h-16 shrink-0 items-center justify-between gap-4 border-b-2 border-gold-400 bg-white px-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <button type="button" id="sidebar-toggle" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Open menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                    </button>
                    <button type="button" id="sidebar-expand-btn"
                            class="hidden rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:inline-flex"
                            aria-label="Expand sidebar">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div class="hidden sm:block">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">@yield('breadcrumb', $portalNav['name'])</p>
                        <p class="text-sm font-semibold text-slate-900">@yield('title', 'Dashboard')</p>
                    </div>
                </div>

                <div class="flex flex-1 items-center justify-end gap-2 sm:gap-3">
                    @if (in_array($portalType, ['admin', 'faculty'], true))
                        <form method="GET" action="{{ route('portal.student-search') }}" class="hidden w-full max-w-md items-center lg:flex">
                            <label for="portal-student-search" class="sr-only">Search students</label>
                            <div class="relative w-full">
                                <x-portal.icon name="search" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
                                <input
                                    id="portal-student-search"
                                    name="q"
                                    type="search"
                                    value="{{ request('q') }}"
                                    placeholder="Search student name or index number"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-gold-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-gold-200"
                                >
                            </div>
                        </form>
                    @endif

                    <a href="{{ route('home') }}" target="_blank" class="hidden items-center gap-1.5 rounded-lg px-3 py-2 text-sm text-slate-500 hover:bg-slate-100 sm:inline-flex">
                        <x-portal.icon name="external" class="h-4 w-4" />
                        Website
                    </a>
                    @if ($portalType === 'admin')
                        <a href="{{ route('admin.settings.index') }}" class="hidden items-center gap-1.5 rounded-lg px-3 py-2 text-sm text-slate-500 hover:bg-slate-100 sm:inline-flex">
                            <x-portal.icon name="settings" class="h-4 w-4" />
                            Site Settings
                        </a>
                    @endif
                    <button type="button" class="relative rounded-lg p-2 text-slate-500 hover:bg-slate-100" aria-label="Notifications">
                        <x-portal.icon name="bell" />
                        @if (($notificationCount ?? 0) > 0)
                            <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                        @endif
                    </button>
                    <div class="hidden h-8 w-px bg-slate-200 sm:block"></div>

                    {{-- User menu --}}
                    <div class="relative" id="user-menu">
                        <button type="button" id="user-menu-button"
                                class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-left hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-gold-400 focus:ring-offset-2"
                                aria-expanded="false"
                                aria-haspopup="true">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gold-100 text-xs font-bold text-gold-800">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden text-left md:block">
                                <p class="text-sm font-medium text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs capitalize text-slate-500">{{ auth()->user()->role->value }}</p>
                            </div>
                            <svg class="hidden h-4 w-4 text-slate-400 md:block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div id="user-menu-dropdown"
                             class="absolute right-0 z-50 mt-2 hidden w-60 origin-top-right rounded-lg bg-white py-1 shadow-lg ring-1 ring-slate-200 focus:outline-none"
                             role="menu">
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                                <span class="mt-2 inline-flex rounded-full bg-gold-50 px-2 py-0.5 text-xs font-medium capitalize text-gold-800 ring-1 ring-gold-200">
                                    {{ auth()->user()->role->value }}
                                </span>
                            </div>

                            <div class="py-1">
                                @foreach ($userMenuItems as $menuItem)
                                    <a href="{{ $menuItem['route'] }}"
                                       class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"
                                       role="menuitem">
                                        <x-portal.icon :name="$menuItem['icon']" class="h-4 w-4 text-slate-400" />
                                        {{ $menuItem['label'] }}
                                    </a>
                                @endforeach
                            </div>

                            <div class="border-t border-slate-100 py-1">
                                <a href="{{ route('home') }}" target="_blank"
                                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50"
                                   role="menuitem">
                                    <x-portal.icon name="external" class="h-4 w-4 text-slate-400" />
                                    View Website
                                </a>
                                <form method="POST" action="{{ route('logout') }}" role="none">
                                    @csrf
                                    <button type="submit"
                                            class="flex w-full items-center gap-2.5 px-4 py-2 text-left text-sm text-rose-600 hover:bg-rose-50"
                                            role="menuitem">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                        </svg>
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    @if (session('status'))
                        <div class="mb-6 flex items-center gap-3 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800 ring-1 ring-emerald-200">
                            <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                            {{ session('status') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
