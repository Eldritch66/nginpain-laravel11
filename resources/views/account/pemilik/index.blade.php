@extends('layouts.account')

@section('title', 'Dashboard Pemilik — Nginapin')

@section('content')
<h1 class="text-2xl font-semibold text-stone-900 mb-6">Dashboard Pemilik</h1>

<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <div class="rounded-2xl border border-stone-200 bg-white p-4">
        <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Total Properti</p>
        <p class="text-2xl font-bold text-stone-900 mt-1">{{ $totalProperti }}</p>
    </div>
    <div class="rounded-2xl border border-stone-200 bg-white p-4">
        <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Disewa</p>
        <p class="text-2xl font-bold text-green-600 mt-1">{{ $totalAktif }}</p>
    </div>
    <div class="rounded-2xl border border-stone-200 bg-white p-4">
        <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Pending</p>
        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $totalPending }}</p>
    </div>
    <div class="rounded-2xl border border-stone-200 bg-white p-4">
        <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Kosong</p>
        <p class="text-2xl font-bold text-stone-400 mt-1">{{ $totalKosong }}</p>
    </div>
    <div class="rounded-2xl border border-stone-200 bg-white p-4">
        <p class="text-xs font-medium text-stone-400 uppercase tracking-wide">Pendapatan Aktif</p>
        <p class="text-2xl font-bold text-stone-900 mt-1">Rp {{ number_format($pendapatanAktif, 0, ',', '.') }}</p>
    </div>
</div>

<a wire:navigate href="/account/pemilik/properti" class="inline-block rounded-xl bg-[#a67f71] px-5 py-2 text-sm font-medium text-white hover:opacity-90 transition">
    Kelola Properti
</a>
@endsection
