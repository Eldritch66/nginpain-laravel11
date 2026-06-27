@extends('layouts.account')

@section('title', 'Detail Properti | Nginapin')

@section('content')
<a wire:navigate href="/account/pemilik/properti" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-800 mb-6 transition">
    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali
</a>

<div class="border border-stone-200 bg-white">
    <div class="relative h-48 sm:h-56 w-full bg-stone-100">
        @if ($properti->foto->isNotEmpty())
            <img src="{{ $properti->foto->first()->url }}" alt="{{ $properti->nama_properti }}" class="h-full w-full object-cover">
        @else
            <div class="flex h-full items-center justify-center">
                <svg class="size-12 text-stone-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
        @endif
    </div>

    @php
        $sewaAktif = $sewaHistory->firstWhere('status_sewa', 'aktif');
        $sewaPending = $sewaHistory->firstWhere('status_sewa', 'pending');
        $status = $sewaAktif ? 'aktif' : ($sewaPending ? 'pending' : 'kosong');
    @endphp

    <div class="p-6 sm:p-8">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-stone-900">{{ $properti->nama_properti }}</h1>
                <p class="text-stone-500 mt-1 flex items-center gap-1.5 text-sm">
                    <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $properti->tipe }} &middot; {{ $properti->kota }}
                </p>
                <p class="text-stone-500 text-sm mt-0.5">{{ $properti->alamat }}</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <span class="text-sm font-semibold text-stone-800">Rp {{ number_format($properti->harga_per_bulan, 0, ',', '.') }} / bln</span>
                @if ($status === 'aktif')
                    <span class="bg-green-100 text-green-700 px-3 py-1 text-xs font-semibold">Aktif</span>
                @elseif ($status === 'pending')
                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 text-xs font-semibold">Pending</span>
                @else
                    <span class="bg-stone-100 text-stone-400 px-3 py-1 text-xs font-semibold">Kosong</span>
                @endif
            </div>
        </div>

        @if ($sewaAktif || $sewaPending)
            @php $currentSewa = $sewaAktif ?? $sewaPending; @endphp
            <div class="border-t border-stone-100 pt-5 mb-6">
                <h2 class="text-sm font-semibold text-stone-700 mb-4">Penyewa Saat Ini</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="flex items-start gap-3 bg-stone-50 p-4">
                        <svg class="size-5 text-stone-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <div>
                            <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Nama Penyewa</p>
                            <p class="text-sm font-semibold text-stone-800 mt-0.5">{{ $currentSewa->penyewa->name ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-stone-50 p-4">
                        <svg class="size-5 text-stone-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <div>
                            <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Email</p>
                            <p class="text-sm font-semibold text-stone-800 mt-0.5">{{ $currentSewa->penyewa->email ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-stone-50 p-4">
                        <svg class="size-5 text-stone-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <div>
                            <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Tanggal Mulai</p>
                            <p class="text-sm font-semibold text-stone-800 mt-0.5">{{ \Carbon\Carbon::parse($currentSewa->tanggal_mulai)->locale('id')->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-stone-50 p-4">
                        <svg class="size-5 text-stone-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <div>
                            <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Tanggal Selesai</p>
                            <p class="text-sm font-semibold text-stone-800 mt-0.5">{{ \Carbon\Carbon::parse($currentSewa->tanggal_selesai)->locale('id')->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-stone-50 p-4">
                        <svg class="size-5 text-stone-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Durasi</p>
                            <p class="text-sm font-semibold text-stone-800 mt-0.5">{{ $currentSewa->durasi_bulan }} bulan</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-stone-50 p-4">
                        <svg class="size-5 text-stone-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Total Harga</p>
                            <p class="text-sm font-semibold text-stone-800 mt-0.5">Rp {{ number_format($currentSewa->total_harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                @if ($sewaPending)
                    <div class="flex gap-3 mt-4">
                        <form method="POST" action="/sewa/{{ $sewaPending->id }}/confirm">
                            @csrf
                            <button type="submit" class="rounded-xl bg-green-600 px-6 py-2 text-sm font-semibold text-white hover:bg-green-700 transition cursor-pointer">
                                Terima
                            </button>
                        </form>
                        <form method="POST" action="/sewa/{{ $sewaPending->id }}/reject">
                            @csrf
                            <button type="submit" class="rounded-xl border border-red-300 px-6 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 transition cursor-pointer"
                                onclick="return confirm('Yakin ingin menolak sewa ini?')">
                                Tolak
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endif

        @if ($status === 'kosong')
            <div class="border-t border-stone-100 pt-5 mb-6">
                <p class="text-stone-400 text-sm">Properti ini sedang tidak disewa.</p>
            </div>
        @endif

        @if ($sewaHistory->isNotEmpty())
            <div class="border-t border-stone-100 pt-5">
                <h2 class="text-sm font-semibold text-stone-700 mb-4">Riwayat Sewa</h2>
                <div class="space-y-2">
                    @foreach ($sewaHistory as $s)
                        <div class="flex items-center justify-between bg-stone-50 px-4 py-3 text-sm">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-stone-800 truncate">{{ $s->penyewa->name ?? '—' }}</p>
                                <p class="text-stone-400 text-xs">{{ \Carbon\Carbon::parse($s->tanggal_mulai)->locale('id')->translatedFormat('dd MMM yyyy') }} — {{ \Carbon\Carbon::parse($s->tanggal_selesai)->locale('id')->translatedFormat('dd MMM yyyy') }}</p>
                            </div>
                            <div class="text-right shrink-0 ml-4">
                                <p class="font-semibold text-stone-800">Rp {{ number_format($s->total_harga, 0, ',', '.') }}</p>
                                <p class="text-xs text-stone-400">{{ $s->durasi_bulan }} bulan</p>
                            </div>
                            <span class="ml-3 text-[11px] font-semibold px-2 py-0.5 {{
                                $s->status_sewa === 'aktif' ? 'bg-green-100 text-green-700' :
                                ($s->status_sewa === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-stone-200 text-stone-500')
                            }}">
                                {{ $s->status_sewa === 'aktif' ? 'Aktif' : ($s->status_sewa === 'pending' ? 'Pending' : ($s->status_sewa === 'dibatalkan' ? 'Dibatalkan' : $s->status_sewa)) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="border-t border-stone-100 pt-0 mt-5 flex">
            <a wire:navigate href="/account/pemilik/properti/{{ $properti->id }}/edit"
                class="group flex items-center justify-center gap-2 uppercase text-xs font-bold text-stone-500 flex-1 py-3 hover:bg-stone-50 transition-colors border-r border-stone-100">
                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit</span>
            </a>
            <div x-data="{ showConfirm: false }" class="flex-1 flex">
                <button type="button" @click="showConfirm = true"
                    class="group flex items-center justify-center gap-2 uppercase text-xs font-bold text-stone-500 w-full flex-1 px-3 hover:bg-red-50 transition-colors hover:text-red-700 cursor-pointer">
                    <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Hapus</span>
                </button>

                <div x-show="showConfirm" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
                    <div class="absolute inset-0 bg-black/50" @click="showConfirm = false"></div>
                    <div class="relative bg-white p-6 shadow-xl border border-stone-200 w-full max-w-sm mx-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-stone-900">Hapus Properti</h3>
                            <button @click="showConfirm = false" class="text-stone-400 hover:text-stone-600 transition cursor-pointer">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <p class="text-sm text-stone-600 mb-6">Apakah Anda yakin ingin menghapus properti ini? Tindakan ini tidak dapat dibatalkan.</p>
                        <div class="flex gap-3 justify-end">
                            <button @click="showConfirm = false" class="px-4 py-2 text-sm font-medium text-stone-600 border border-stone-300 hover:bg-stone-50 transition cursor-pointer">
                                Batal
                            </button>
                            <form method="POST" action="/account/pemilik/properti/{{ $properti->id }}/delete">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 transition cursor-pointer"
                                    @click="setTimeout(() => showConfirm = false, 100)">
                                    Ya, Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
