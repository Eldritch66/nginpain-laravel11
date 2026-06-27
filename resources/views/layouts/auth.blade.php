<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/js/app.js')
    @livewireStyles
    <title>@yield('title', 'Nginapin')</title>
</head>
<body class="antialiased w-full">
    {{ $slot }}

    @livewireScripts
</body>
</html>
