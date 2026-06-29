@extends('layouts.account')

@section('title', 'Detail Sewa — Nginapin')

@section('content')
<a wire:navigate href="/account/sewa" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-800 mb-6 transition">
    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali
</a>

<div class="border border-stone-200 bg-white">
    @if ($sewa->properti->foto->isNotEmpty())
        <div class="h-48 sm:h-56 w-full bg-stone-100">
            <img src="{{ $sewa->properti->foto->first()->url }}" alt="" class="w-full h-full object-cover">
        </div>
    @endif
    <div class="p-6 sm:p-8">
        <div class="flex items-center justify-between mb-4">
            <div class="min-w-0">
                <h1 class="text-xl font-semibold text-stone-900">{{ $sewa->properti->nama_properti }}</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-[11px] font-mono text-orange-600 font-medium">{{ $sewa->kode_booking }}</span>
                </div>
            </div>
            @if ($sewa->status_sewa === 'aktif')
                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Aktif</span>
            @elseif ($sewa->status_sewa === 'pending')
                <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">Pending</span>
            @else
                <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Dibatalkan</span>
            @endif
        </div>

        <div class="space-y-3 text-sm text-stone-600">
            <div class="flex justify-between">
                <span>Tanggal Mulai</span>
                <span class="font-medium text-stone-800">{{ \Carbon\Carbon::parse($sewa->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tanggal Selesai</span>
                <span class="font-medium text-stone-800">{{ \Carbon\Carbon::parse($sewa->tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Durasi</span>
                <span class="font-medium text-stone-800">{{ $sewa->durasi_bulan }} bulan</span>
            </div>
            <div class="flex justify-between">
                <span>Total Harga</span>
                <span class="font-semibold text-stone-900">Rp {{ number_format($sewa->total_harga, 0, ',', '.') }}</span>
            </div>
            @if ($sewa->biaya_layanan)
            <div class="flex justify-between">
                <span>Biaya Layanan (2.5%)</span>
                <span class="text-stone-900">Rp {{ number_format($sewa->biaya_layanan, 0, ',', '.') }}</span>
            </div>
            @endif
            @php
                $biayaPemeliharaan = (int) ceil($sewa->total_harga * 0.05);
            @endphp
            <div class="flex justify-between">
                <span>Biaya Pemeliharaan (5%)</span>
                <span class="text-stone-900">Rp {{ number_format($biayaPemeliharaan, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between border-t border-stone-100 pt-3 font-semibold text-stone-900">
                <span>Total Dibayar</span>
                <span>Rp {{ number_format($sewa->total_harga + $sewa->biaya_layanan + $biayaPemeliharaan, 0, ',', '.') }}</span>
            </div>
        </div>

        @php
            $pembayaranLunas = $sewa->pembayaran->firstWhere('status', 'lunas');
        @endphp

        @if ($sewa->status_sewa === 'dibatalkan' && $pembayaranLunas)
            <div class="mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 flex items-start gap-3">
                <svg class="size-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p class="font-semibold">Pembatalan Sewa</p>
                    <p class="mt-1 text-green-700">Uang dikembalikan sebesar <strong>Rp {{ number_format($pembayaranLunas->jumlah, 0, ',', '.') }}</strong></p>
                </div>
            </div>
        @endif

        @if ($sewa->status_sewa === 'pending')
            <form method="POST" action="/sewa/{{ $sewa->id }}/cancel" class="mt-6">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-red-300 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition cursor-pointer"
                    onclick="return confirm('Yakin ingin membatalkan sewa ini?')">
                    Batalkan Sewa
                </button>
            </form>
        @endif

        @if ($sewa->status_sewa === 'dibatalkan')
            <form method="POST" action="/sewa/{{ $sewa->id }}/delete" class="mt-6">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-red-300 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition cursor-pointer"
                    onclick="return confirm('Yakin ingin menghapus sewa ini? Tindakan ini tidak dapat dibatalkan.')">
                    Hapus Sewa
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
