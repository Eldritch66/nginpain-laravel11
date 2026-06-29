<div class="min-h-screen bg-stone-50">
    <div class="mx-auto max-w-[1750px] px-2 lg:px-0 py-4">
        @if (session('error'))
            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-center">
                <p class="text-sm font-semibold text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <a wire:navigate href="/properti" class="inline-flex items-center gap-1.5 text-sm text-stone-500 hover:text-stone-700 mb-6 transition">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>

        <div class="grid gap-6 lg:grid-cols-[1.35fr_0.9fr] lg:items-start">
            <div class="flex flex-col gap-4">
                <div class="relative overflow-hidden rounded-[28px] border border-stone-200 bg-white shadow-sm h-[clamp(360px,72vh,760px)]">
                    @if ($properti->foto->isNotEmpty())
                        <img src="{{ $properti->foto->first()->url }}" alt="{{ $properti->nama_properti }}" class="h-full w-full object-cover object-center">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-stone-400">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                <span class="text-sm font-medium uppercase tracking-[0.18em]">Foto tidak tersedia</span>
                            </div>
                        </div>
                    @endif
                </div>

                @if ($properti->unit)
                    <div class="rounded-[28px] border border-stone-200 bg-white p-5 shadow-sm">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-400 mb-4">Spesifikasi Unit</p>
                        <div class="grid grid-cols-3 gap-3">
                            @if ($properti->unit->luas_bangunan)
                                <div class="flex flex-col items-center gap-1.5 rounded-2xl bg-stone-50 px-3 py-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm">
                                        <svg class="size-3.5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                    </div>
                                    <span class="text-sm font-semibold text-stone-800">{{ $properti->unit->luas_bangunan }} m²</span>
                                    <span class="text-[10px] text-stone-400 uppercase tracking-wide">Luas</span>
                                </div>
                            @endif
                            <div class="flex flex-col items-center gap-1.5 rounded-2xl bg-stone-50 px-3 py-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm">
                                    <svg class="size-3.5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-stone-800">{{ $properti->unit->jumlah_kamar_tidur }}</span>
                                <span class="text-[10px] text-stone-400 uppercase tracking-wide">Kamar Tidur</span>
                            </div>
                            <div class="flex flex-col items-center gap-1.5 rounded-2xl bg-stone-50 px-3 py-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm">
                                    <svg class="size-3.5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-stone-800">{{ $properti->unit->jumlah_kamar_mandi }}</span>
                                <span class="text-[10px] text-stone-400 uppercase tracking-wide">Kamar Mandi</span>
                            </div>
                            <div class="flex flex-col items-center gap-1.5 rounded-2xl bg-stone-50 px-3 py-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm">
                                    <svg class="size-3.5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-stone-800">{{ $properti->unit->kapasitas_penghuni }}</span>
                                <span class="text-[10px] text-stone-400 uppercase tracking-wide">Penghuni</span>
                            </div>
                            <div class="flex flex-col items-center gap-1.5 rounded-2xl bg-stone-50 px-3 py-3">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white shadow-sm">
                                    <svg class="size-3.5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <span class="text-sm font-semibold text-stone-800">Lantai {{ $properti->unit->lantai }}</span>
                                <span class="text-[10px] text-stone-400 uppercase tracking-wide">Lantai</span>
                            </div>
                        </div>
                        @if ($properti->unit->keterangan)
                            <p class="mt-4 text-sm text-stone-500">{{ $properti->unit->keterangan }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="lg:sticky lg:top-6">
                <div class="rounded-[28px] border border-stone-200 bg-white p-6 shadow-sm">
                    <div class="mb-4 inline-flex items-center gap-1.5 rounded-full border border-stone-200 bg-stone-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-stone-500">
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        {{ $properti->tipe }}
                    </div>

                    <div class="flex items-start justify-between gap-4">
                        <h1 class="text-3xl font-semibold tracking-tight text-stone-950 sm:text-4xl">{{ $properti->nama_properti }}</h1>
                        <span class="shrink-0 text-xs font-mono text-stone-400 mt-1.5">{{ $properti->kode_properti }}</span>
                    </div>

                    <div class="mt-3 flex items-center gap-1.5 text-sm text-stone-500">
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $properti->kota }}, Jawa Barat, Indonesia
                    </div>

                    <div class="my-6 border-t border-stone-100"></div>

                    <div class="mb-5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-400">Alamat</p>
                        <div class="mt-2 flex items-start gap-2 text-sm leading-6 text-stone-600">
                            <svg class="mt-1 size-3.5 shrink-0 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $properti->alamat }}</span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-400">Pemilik</p>
                        <div class="mt-2 flex items-center gap-2 text-sm text-stone-600">
                            <svg class="size-3.5 shrink-0 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>{{ $properti->pemilik?->name ?? '—' }}</span>
                        </div>
                    </div>

                    <div class="border-t border-stone-100 pt-5">
                        <span class="text-2xl font-semibold tracking-tight text-stone-950">Rp {{ number_format($properti->harga_per_dua_bulan, 0, ',', '.') }}</span>
                        <span class="ml-2 text-sm text-stone-400">/ 2 bulan</span>
                    </div>

                    @if ($this->is_pemilik)
                        <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-6 text-center">
                            <p class="text-lg font-semibold text-amber-700">Properti Anda</p>
                            <p class="mt-1 text-sm text-amber-600">Anda adalah pemilik dari properti ini.</p>
                        </div>
                    @elseif ($this->is_tersedia)
                        <div class="mt-6">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-stone-400 mb-2">Durasi Sewa</p>
                            <div class="flex items-center justify-between rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3">
                                <button type="button" wire:click="decrementMonths" @if ($months <= 2) disabled @endif class="flex h-8 w-8 items-center justify-center rounded-full border border-stone-300 text-stone-600 transition hover:border-stone-400 hover:bg-white disabled:cursor-not-allowed disabled:opacity-40">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                </button>
                                <span class="text-lg font-semibold text-stone-900">{{ $months }} Bulan</span>
                                <button type="button" wire:click="incrementMonths" class="flex h-8 w-8 items-center justify-center rounded-full border border-stone-300 text-stone-600 transition hover:border-stone-400 hover:bg-white">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                            <div class="mt-2 flex justify-between text-sm text-stone-500">
                                <span>{{ $this->start_date->format('d M Y') }} — {{ $this->end_date->format('d M Y') }}</span>
                                <span class="font-medium text-stone-700">{{ $months }} bulan</span>
                            </div>
                        </div>

                        <div class="mt-4 rounded-2xl bg-stone-50 px-4 py-4 text-sm text-stone-600">
                            <div class="flex justify-between">
                                <span>2 bulan pertama</span>
                                <span>Rp {{ number_format($properti->harga_per_dua_bulan, 0, ',', '.') }}</span>
                            </div>
                            @if ($this->extra_months > 0)
                                <div class="mt-2 flex justify-between">
                                    <span>{{ $this->extra_months }} bulan tambahan × Rp {{ number_format($properti->harga_per_bulan, 0, ',', '.') }}</span>
                                    <span>Rp {{ number_format($this->extra_months * $properti->harga_per_bulan, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="mt-2 flex justify-between font-medium text-stone-800">
                                <span>Total Sewa</span>
                                <span>Rp {{ number_format($this->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="mt-2 flex justify-between">
                                <span>Biaya layanan</span>
                                <span>Rp {{ number_format($this->service_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="mt-2 flex justify-between">
                                <span>Biaya Pemeliharaan (5%)</span>
                                <span>Rp {{ number_format($this->pemeliharaan, 0, ',', '.') }}</span>
                            </div>
                            <div class="mt-3 flex justify-between border-t border-stone-200 pt-3 font-semibold text-stone-950">
                                <span>Total Pembayaran</span>
                                <span>Rp {{ number_format($this->grand_total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="button" wire:click="book" class="mt-5 w-full text-2xl bg-orange-600 text-white py-3 rounded-xl font-semibold hover:opacity-90 transition cursor-pointer">
                            Sewa Sekarang
                        </button>
                    @else
                        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-6 text-center">
                            <p class="text-lg font-semibold text-red-700">Tidak Tersedia</p>
                            <p class="mt-1 text-sm text-red-600">Properti ini sedang disewa oleh penyewa lain.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
