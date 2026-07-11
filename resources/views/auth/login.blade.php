<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Access — {{ config('app.name') }}</title>
    <x-website.favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-50 to-gold-50 antialiased">
    <div class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <x-website.logo size="lg" class="mx-auto inline-flex flex-col items-center sm:flex-row" />
                <h1 class="website-section-heading mt-4 text-brand-900">Portal Access</h1>
                <p class="mt-1 text-sm text-slate-500">Sign in to the student or faculty portal</p>
            </div>

            <div class="rounded-lg bg-white p-8 shadow-lg ring-1 ring-slate-200">
                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Account Type</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach (['student' => 'Student', 'faculty' => 'Faculty'] as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" name="role" value="{{ $value }}" class="peer sr-only" @checked(old('role', 'student') === $value)>
                                    <span class="flex items-center justify-center rounded-lg border border-slate-200 px-3 py-2.5 text-sm font-medium text-slate-600 peer-checked:border-gold-500 peer-checked:bg-gold-50 peer-checked:text-brand-800">
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label for="login" class="mb-1.5 block text-sm font-medium text-slate-700">Username or Email</label>
                        <input type="text" name="login" id="login" value="{{ old('login') }}" required
                               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200"
                               placeholder="Username or email address">
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200"
                               placeholder="Your password">
                    </div>

                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-gold-600 accent-gold-500">
                        Remember me
                    </label>

                    <button type="submit" class="w-full rounded-lg bg-gold-500 py-3 text-sm font-semibold text-brand-900 hover:bg-gold-400">
                        Sign in
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-500">
                    <a href="{{ route('home') }}" class="font-medium text-brand-700 hover:underline">← Back to website</a>
                </p>

                <p class="mt-4 text-center text-xs text-slate-500">
                    Admin? <a href="{{ route('admin.login') }}" class="font-semibold text-brand-700 hover:underline">Use Admin Login</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
