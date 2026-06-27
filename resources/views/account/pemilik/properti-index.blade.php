@extends('layouts.account')

@section('title', 'Properti Saya | Nginapin')

@section('content')
<div class="flex items-center justify-between mb-7">
    <h2 class="font-semibold text-2xl text-stone-900">Properti Saya</h2>
    <a wire:navigate href="/account/pemilik/properti/tambah" class="inline-flex items-center gap-2 bg-[#a67f71] px-5 py-2 text-sm font-medium text-white hover:opacity-90 transition">
        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Properti
    </a>
</div>

@if ($propertiList->isEmpty())
    <div class="border border-stone-200 bg-white p-10 text-center">
        <svg class="size-12 mx-auto text-stone-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <p class="mt-4 text-stone-500">Belum ada properti. Tambahkan properti pertama Anda!</p>
        <a wire:navigate href="/account/pemilik/properti/tambah" class="mt-4 inline-flex items-center gap-2 bg-[#a67f71] px-5 py-2 text-sm font-medium text-white hover:opacity-90 transition">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Properti
        </a>
    </div>
@else
    <div class="flex flex-col gap-2">
        @foreach ($propertiList as $properti)
            @php
                $sewaAktif = $properti->sewa->firstWhere('status_sewa', 'aktif');
                $sewaPending = $properti->sewa->firstWhere('status_sewa', 'pending');
                $status = $sewaAktif ? 'aktif' : ($sewaPending ? 'pending' : 'kosong');
            @endphp
            <div class="flex flex-col border border-stone-200" x-data="{ showConfirm: false }">
                <div class="flex">
                    <div class="relative h-28 sm:h-32 aspect-square shrink-0 bg-stone-100">
                        @if ($properti->foto->isNotEmpty())
                            <img src="{{ $properti->foto->first()->url }}" alt="{{ $properti->nama_properti }}" class="size-full object-cover border-r border-stone-200">
                        @else
                            <div class="flex size-full items-center justify-center border-r border-stone-200">
                                <svg class="size-8 text-stone-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-1 min-w-0">
                        <div class="flex flex-col justify-center gap-1.5 px-3 sm:px-6 py-3 flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <h3 class="text-base sm:text-xl font-semibold text-stone-900 truncate">{{ $properti->nama_properti }}</h3>
                                    <p class="text-xs sm:text-sm text-stone-500">{{ $properti->tipe }} &middot; {{ $properti->kota }} &middot; Rp {{ number_format($properti->harga_per_bulan, 0, ',', '.') }}/bln</p>
                                </div>
                                <div class="flex flex-col items-end shrink-0">
                                    @if ($status === 'aktif')
                                        <span class="bg-green-800 text-green-200 h-6 px-2 sm:h-7 sm:px-3 uppercase text-[10px] sm:text-xs font-bold flex items-center">Aktif</span>
                                    @elseif ($status === 'pending')
                                        <span class="bg-yellow-200 text-yellow-800 h-6 px-2 sm:h-7 sm:px-3 uppercase text-[10px] sm:text-xs font-bold flex items-center">Pending</span>
                                    @else
                                        <span class="bg-stone-200 text-stone-500 h-6 px-2 sm:h-7 sm:px-3 uppercase text-[10px] sm:text-xs font-bold flex items-center">Kosong</span>
                                    @endif
                                </div>
                            </div>

                            <div class="border-t border-stone-100 pt-2">
                                @if ($sewaAktif && $sewaAktif->relationLoaded('penyewa'))
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-1.5 text-sm text-stone-700 min-w-0">
                                            <svg class="size-3.5 shrink-0 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            <span class="font-medium truncate">{{ $sewaAktif->penyewa->name }}</span>
                                            <span class="text-stone-400 text-xs shrink-0">({{ $sewaAktif->penyewa->email }})</span>
                                        </div>
                                        <div class="flex flex-col items-end gap-0.5 shrink-0">
                                            <span class="text-[11px] sm:text-xs text-stone-500 text-right leading-tight">
                                                {{ \Carbon\Carbon::parse($sewaAktif->tanggal_mulai)->locale('id')->translatedFormat('dd MMM yyyy') }}
                                                @if ($sewaAktif->tanggal_selesai)
                                                    — {{ \Carbon\Carbon::parse($sewaAktif->tanggal_selesai)->locale('id')->translatedFormat('dd MMM yyyy') }}
                                                @endif
                                            </span>
                                            <span class="text-[11px] sm:text-xs font-semibold text-stone-800">
                                                {{ $sewaAktif->durasi_bulan }} bulan &middot; Rp {{ number_format($sewaAktif->total_harga, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-stone-400">Belum ada penyewa</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="hidden sm:flex flex-col border-l border-stone-200 w-[100px] shrink-0">
                        <a wire:navigate href="/account/pemilik/properti/{{ $properti->id }}"
                            class="group flex items-center justify-center gap-2 uppercase text-xs font-bold text-stone-500 border-b border-stone-200 flex-1 px-3 hover:bg-stone-100 transition-colors">
                            <svg class="size-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span>Detail</span>
                        </a>
                        <button type="button" @click="showConfirm = true"
                            class="group flex items-center justify-center gap-2 uppercase text-xs font-bold text-stone-500 w-full flex-1 px-3 hover:bg-red-50 transition-colors hover:text-red-700 cursor-pointer">
                            <svg class="size-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            <span>Hapus</span>
                        </button>
                    </div>
                </div>

                <div class="flex sm:hidden border-t border-stone-200">
                    <a wire:navigate href="/account/pemilik/properti/{{ $properti->id }}"
                        class="group flex items-center justify-center gap-2 flex-1 uppercase text-xs font-bold text-stone-500 border-r border-stone-200 py-2.5 hover:bg-stone-100 transition-colors">
                        <svg class="size-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>Detail</span>
                    </a>
                    <button type="button" @click="showConfirm = true"
                        class="group flex items-center justify-center gap-2 uppercase text-xs font-bold text-stone-500 flex-1 py-2.5 hover:bg-red-50 transition-colors hover:text-red-700 cursor-pointer">
                        <svg class="size-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus</span>
                    </button>
                </div>

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
                            <button @click="showConfirm = false" class="px-4 py-2 text-sm font-medium text-stone-600 border border-stone-300 hover:bg-stone-50 transition cursor-pointer">Batal</button>
                            <form method="POST" action="/account/pemilik/properti/{{ $properti->id }}/delete">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 transition cursor-pointer">Ya, Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
