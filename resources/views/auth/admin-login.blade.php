<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — {{ config('app.name') }}</title>
    <x-website.favicon />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-900 to-brand-800 antialiased">
    <div class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <x-website.logo size="lg" class="mx-auto inline-flex flex-col items-center sm:flex-row" />
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-white">Admin Access</h1>
                <p class="mt-1 text-sm text-brand-200">Sign in to manage the portal and public website</p>
            </div>

            <div class="rounded-xl bg-white p-8 shadow-2xl ring-1 ring-black/10">
                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="login" class="mb-1.5 block text-sm font-medium text-slate-700">Username or Email</label>
                        <input type="text" name="login" id="login" value="{{ old('login') }}" required
                               class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-200"
                               placeholder="Admin username or email">
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
                        Sign in as Admin
                    </button>
                </form>

                <div class="mt-6 space-y-2 text-center text-sm">
                    <a href="{{ route('login') }}" class="font-medium text-brand-700 hover:underline">Student / Faculty Login</a>
                    <div class="text-slate-400">·</div>
                    <a href="{{ route('home') }}" class="text-slate-500 hover:underline">Back to website</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

