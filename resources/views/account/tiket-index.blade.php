@extends('layouts.account')

@section('title', 'Tiket Bantuan — Nginapin')

@section('content')
    <div class="max-w-4xl">
        <div class="mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-stone-900">Tiket Bantuan</h1>
            <p class="mt-1 text-sm text-stone-500">Riwayat tiket bantuan yang pernah kamu kirim.</p>
        </div>

        @forelse ($tiketList as $tiket)
            <div class="mb-4 rounded-2xl border border-stone-200 bg-white shadow-sm">
                <div class="flex items-start justify-between gap-4 border-b border-stone-100 px-5 py-4">
                    <div class="min-w-0 flex-1">
                        <h3 class="text-base font-semibold text-stone-900">{{ $tiket->judul }}</h3>
                        <p class="mt-0.5 text-sm text-stone-500">
                            {{ ['teknis' => 'Teknis', 'pembayaran' => 'Pembayaran', 'properti' => 'Properti', 'akun' => 'Akun', 'lainnya' => 'Lainnya'][$tiket->kategori] ?? $tiket->kategori }} &middot; {{ $tiket->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <span class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $tiket->status === 'selesai' ? 'bg-green-100 text-green-700' : ($tiket->status === 'ditutup' ? 'bg-stone-100 text-stone-500' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ['diproses' => 'Diproses', 'selesai' => 'Selesai', 'ditutup' => 'Ditutup'][$tiket->status] ?? $tiket->status }}
                    </span>
                </div>

                <div class="px-5 py-4">
                    <div class="rounded-xl bg-stone-50 px-4 py-3 text-sm text-stone-700 leading-relaxed">
                        {{ $tiket->pesan }}
                    </div>

                    @if ($tiket->balasan_admin)
                        <div class="mt-4 rounded-xl border border-orange-100 bg-orange-50 px-4 py-3">
                            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-orange-600 mb-2">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                Balasan Admin
                                @if ($tiket->penjawab)
                                    <span class="font-normal normal-case text-orange-500">— {{ $tiket->penjawab->nama }}</span>
                                @endif
                            </div>
                            <p class="text-sm text-stone-700 leading-relaxed">{{ $tiket->balasan_admin }}</p>
                            @if ($tiket->dijawab_pada)
                                <p class="mt-2 text-xs text-stone-400">{{ \Carbon\Carbon::parse($tiket->dijawab_pada)->format('d M Y, H:i') }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-stone-200 bg-white px-6 py-12 text-center">
                <svg class="mx-auto size-12 text-stone-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <h3 class="mt-4 text-base font-semibold text-stone-700">Belum ada tiket</h3>
                <p class="mt-1 text-sm text-stone-500">Kamu belum pernah mengirim tiket bantuan. Silakan kirim melalui halaman beranda.</p>
                <a wire:navigate href="/" class="mt-4 inline-flex items-center gap-1.5 text-sm font-medium text-orange-600 hover:text-orange-700 transition">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Ke Beranda
                </a>
            </div>
        @endforelse
    </div>
@endsection
