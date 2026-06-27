<!DOCTYPE html>
<html lang="en" class="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/js/app.js')
    @livewireStyles
    <title>@yield('title', 'Nginapin')</title>
</head>
<body class="antialiased w-full bg-stone-50 min-h-screen flex flex-col">
    <x-header />



    <main class="flex-1">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <x-footer />

    @livewireScripts
</body>
</html>
