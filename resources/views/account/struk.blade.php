@extends('layouts.account')

@section('title', 'Bukti Pemesanan — Nginapin')

@section('content')
<div class="max-w-2xl mx-auto">
    <a wire:navigate href="/account/sewa" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-800 mb-6 transition">
        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>

    <div class="rounded-2xl border border-stone-200 bg-white overflow-hidden">
        <div class="bg-stone-50 px-6 py-5 border-b border-stone-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/logo.png" alt="Nginapin" class="h-7 w-auto">
                <span class="text-sm text-stone-300 hidden sm:inline">/</span>
                <h1 class="text-sm font-medium text-stone-700">Bukti Pemesanan</h1>
            </div>
            <span class="text-xs text-stone-400 font-mono">{{ $sewa->kode_booking }}</span>
        </div>

        <div class="px-6 py-6 space-y-6">
            @if ($sewa->properti->foto->isNotEmpty())
                <div class="h-40 rounded-xl bg-stone-100 overflow-hidden">
                    <img src="{{ $sewa->properti->foto->first()->url }}" alt="" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="flex items-start justify-between">
                <div>
                    <div>
                        <h2 class="text-lg font-semibold text-stone-900">{{ $sewa->properti->nama_properti }}</h2>
                        <p class="text-sm text-stone-500 mt-0.5">
                            <span class="font-mono">{{ $sewa->properti->kode_properti }}</span>
                            &middot; {{ $sewa->properti->tipe }} &middot; {{ $sewa->properti->kota }}
                        </p>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700 shrink-0">
                    Menunggu Konfirmasi
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="bg-stone-50 rounded-xl p-4">
                    <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Penyewa</p>
                    <p class="font-semibold text-stone-800 mt-1">{{ $sewa->penyewa->name }}</p>
                    <p class="text-stone-500 text-xs">{{ $sewa->penyewa->email }}</p>
                </div>
                <div class="bg-stone-50 rounded-xl p-4">
                    <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Pemilik</p>
                    <p class="font-semibold text-stone-800 mt-1">{{ $sewa->properti->pemilik->name ?? '—' }}</p>
                </div>
                <div class="bg-stone-50 rounded-xl p-4">
                    <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Tanggal Mulai</p>
                    <p class="font-semibold text-stone-800 mt-1">{{ \Carbon\Carbon::parse($sewa->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}</p>
                </div>
                <div class="bg-stone-50 rounded-xl p-4">
                    <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Tanggal Selesai</p>
                    <p class="font-semibold text-stone-800 mt-1">{{ \Carbon\Carbon::parse($sewa->tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}</p>
                </div>
                <div class="bg-stone-50 rounded-xl p-4">
                    <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Durasi</p>
                    <p class="font-semibold text-stone-800 mt-1">{{ $sewa->durasi_bulan }} bulan</p>
                </div>
                <div class="bg-stone-50 rounded-xl p-4">
                    <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Status Pembayaran</p>
                    <p class="font-semibold text-stone-800 mt-1">
                        @php
                            $pay = $sewa->pembayaran->first();
                        @endphp
                        @if ($pay)
                            @switch($pay->status)
                                @case('lunas')
                                    <span class="text-green-600">Lunas</span>
                                    @break
                                @case('menunggu')
                                    <span class="text-yellow-600">Menunggu Pembayaran</span>
                                    @break
                                @case('ditolak')
                                    <span class="text-red-600">Ditolak</span>
                                    @break
                                @case('kadaluarsa')
                                    <span class="text-gray-600">Kadaluarsa</span>
                                    @break
                                @default
                                    {{ $pay->status }}
                            @endswitch
                        @else
                            —
                        @endif
                    </p>
                </div>
                <div class="bg-stone-50 rounded-xl p-4">
                    <p class="text-xs text-stone-400 uppercase tracking-wide font-medium">Metode</p>
                    <p class="font-semibold text-stone-800 mt-1">{{ $pay->metode ?? '—' }}</p>
                </div>
            </div>

            <div class="border-t border-stone-200 pt-5">
                <h3 class="text-sm font-semibold text-stone-700 mb-3">Rincian Biaya</h3>
                <div class="space-y-2 text-sm">
                    @php
                        $basePrice = $sewa->properti->harga_per_dua_bulan;
                        $extraMonths = max(0, $sewa->durasi_bulan - 2);
                        $extraPrice = $extraMonths * $sewa->properti->harga_per_bulan;
                        $biayaLayanan = $sewa->biaya_layanan;
                        $biayaPemeliharaan = (int) ceil($sewa->total_harga * 0.05);
                        $grandTotal = $sewa->total_harga + $biayaLayanan + $biayaPemeliharaan;
                    @endphp
                    <div class="flex justify-between text-stone-600">
                        <span>Harga 2 bulan pertama</span>
                        <span>Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                    </div>
                    @if ($extraMonths > 0)
                        <div class="flex justify-between text-stone-600">
                            <span>Tambahan {{ $extraMonths }} bulan</span>
                            <span>Rp {{ number_format($extraPrice, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-stone-600">
                        <span>Biaya Layanan (2.5%)</span>
                        <span>Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-stone-600">
                        <span>Biaya Pemeliharaan (5%)</span>
                            <span>Rp {{ number_format($biayaPemeliharaan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-stone-900 border-t border-stone-200 pt-2">
                        <span>Total Dibayar</span>
                        <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @php
                $pay = $sewa->pembayaran->first();
            @endphp

            @if ($pay && $pay->status === 'lunas')
                <div class="rounded-xl bg-yellow-50 border border-yellow-200 px-4 py-3 text-sm text-yellow-800 flex items-start gap-3">
                    <svg class="size-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    <div>
                        <p class="font-semibold">Menunggu Konfirmasi Pemilik</p>
                        <p class="mt-1 text-yellow-700">Pembayaran Anda telah diterima. Pemilik properti akan mengkonfirmasi sewa Anda. Kami akan memberi tahu Anda setelah dikonfirmasi.</p>
                    </div>
                </div>
            @elseif ($pay && $pay->status === 'menunggu')
                <div class="rounded-xl bg-blue-50 border border-blue-200 px-4 py-3 text-sm text-blue-800 flex items-start gap-3">
                    <svg class="size-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-semibold">Menunggu Pembayaran</p>
                        <p class="mt-1 text-blue-700">Pembayaran Anda sedang diproses. Silakan selesaikan pembayaran melalui halaman Midtrans.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <form action="{{ route('payment.check-status') }}" method="POST">
                        @csrf
                        <input type="hidden" name="sewa_id" value="{{ $sewa->id }}">
                        <button type="submit" class="w-full rounded-xl bg-[#a67f71] px-6 py-3 text-sm font-semibold text-white hover:opacity-90 transition">
                            Cek Status Pembayaran
                        </button>
                    </form>
                    <a href="/account/sewa" class="block w-full text-center rounded-xl border border-stone-300 px-6 py-3 text-sm font-semibold text-stone-700 hover:bg-stone-50 transition">
                        Kembali ke Sewa Saya
                    </a>
                </div>
            @elseif ($pay && ($pay->status === 'ditolak' || $pay->status === 'kadaluarsa'))
                <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800 flex items-start gap-3">
                    <svg class="size-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-semibold">Pembayaran {{ $pay->status === 'ditolak' ? 'Ditolak' : 'Kadaluarsa' }}</p>
                        <p class="mt-1 text-red-700">Pembayaran gagal. Silakan lakukan sewa ulang untuk mencoba lagi.</p>
                    </div>
                </div>
                <a wire:navigate href="/account/sewa" class="block w-full text-center rounded-xl bg-orange-600 px-6 py-3 text-sm font-semibold text-white hover:bg-orange-700 transition">
                    Lihat Sewa Saya
                </a>
            @else
                <a wire:navigate href="/account/sewa" class="block w-full text-center rounded-xl bg-orange-600 px-6 py-3 text-sm font-semibold text-white hover:bg-orange-700 transition">
                    Lihat Sewa Saya
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
