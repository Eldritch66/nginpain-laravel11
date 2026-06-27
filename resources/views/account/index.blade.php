@extends('layouts.account')

@section('title', 'Dashboard — Nginapin')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-semibold text-stone-900">Halo, {{ explode(' ', $user->name)[0] }}!</h1>
        <p class="text-sm text-stone-500 mt-1">Selamat datang di dashboard Nginapin</p>
    </div>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <a wire:navigate href="/account/sewa" class="rounded-2xl border border-stone-200 bg-white p-6 hover:shadow-md transition">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100">
                <svg class="size-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h2 class="font-semibold text-stone-900">Sewa Saya</h2>
                <p class="text-sm text-stone-500">Kelola sewa properti Anda</p>
            </div>
        </div>
    </a>
</div>
@endsection
