<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Nginapin</title>
    @vite('resources/js/app.js')
    @livewireStyles
</head>
<body class="antialiased min-h-screen flex items-center justify-center bg-gradient-to-b from-white via-orange-50/30 to-neutral-50">
    <div class="w-full max-w-md mx-4">
        <div class="rounded-xl border border-neutral-200 bg-white p-8 shadow-sm">
            <a wire:navigate href="/" class="flex justify-center mb-6">
                <img src="/logo.png" alt="Nginapin" class="h-8 w-auto">
            </a>
            <h1 class="text-2xl font-bold text-center text-neutral-900">Masuk</h1>
            <p class="mt-2 text-sm text-center text-neutral-500">Masuk untuk melanjutkan</p>

            @if (session('success'))
                <div class="mt-4 rounded-lg bg-green-50 p-3 text-sm text-green-700 flex items-center gap-2">
                    <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-600 flex items-center gap-2">
                    <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-neutral-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500" placeholder="email@contoh.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700">Password</label>
                    <input type="password" name="password" required class="mt-1 block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500" placeholder="Minimal 6 karakter">
                </div>

                <button type="submit" class="w-full rounded-xl bg-orange-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-orange-700 transition cursor-pointer">
                    Masuk
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-neutral-200"></span></div>
                    <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-2 text-neutral-400">Atau</span></div>
                </div>

                <a href="/auth/google/redirect" class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl border border-neutral-300 px-4 py-2.5 text-sm font-medium text-neutral-700 hover:bg-neutral-50 transition">
                    <svg class="h-5 w-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Masuk dengan Google
                </a>
            </div>

            <p class="mt-6 text-center text-sm text-neutral-500">
                Belum punya akun? <a wire:navigate href="/register" class="font-medium text-orange-600 hover:text-orange-700">Daftar</a>
            </p>
        </div>
        <div class="mt-6 grid grid-cols-3 gap-4 text-center text-xs text-neutral-400">
            <div class="flex flex-col items-center gap-1">
                <svg class="size-4 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span>Trusted</span>
            </div>
            <div class="flex flex-col items-center gap-1">
                <svg class="size-4 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Secure</span>
            </div>
            <div class="flex flex-col items-center gap-1">
                <svg class="size-4 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>24/7 Care</span>
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>
