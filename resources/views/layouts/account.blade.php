<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Akun — Nginapin')</title>
    @vite('resources/js/app.js')
    @livewireStyles
</head>
<body class="antialiased bg-stone-50 min-h-screen">
    <x-header />

    @if (session('success'))
        <div class="mx-auto max-w-[1750px] px-4 pt-4">
            <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700 flex items-center gap-2">
                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mx-auto max-w-[1750px] px-4 pt-4">
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 flex items-center gap-2">
                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="min-h-[calc(100vh-4rem)] w-full px-4 py-4">
        <section class="w-full max-w-[1750px] mx-auto flex flex-col sm:grid sm:grid-cols-[350px_1fr] sm:gap-10 sm:min-h-[calc(100vh-8rem)]">
            <x-navigation-profile />
            <div class="flex-1 py-4 sm:py-0">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </section>
    </div>

    @livewireScripts
</body>
</html>
