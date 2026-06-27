<div class="min-h-screen bg-stone-50">
    <div class="mx-auto max-w-2xl px-4 py-8">
        <h1 class="text-3xl font-semibold text-stone-900 mb-6">Pembayaran</h1>

        @if (session('error'))
            <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        <div class="rounded-[28px] border border-stone-200 bg-white p-6 shadow-sm mb-6">
            <h2 class="text-xl font-semibold text-stone-900 mb-4">{{ $this->sewa->properti->nama_properti }}</h2>
            <div class="space-y-2 text-sm text-stone-600">
                <div class="flex justify-between">
                    <span>Tanggal Sewa</span>
                    <span>{{ \Carbon\Carbon::parse($this->sewa->tanggal_mulai)->locale('id')->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($this->sewa->tanggal_selesai)->locale('id')->translatedFormat('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Durasi</span>
                    <span>{{ $this->sewa->durasi_bulan }} bulan</span>
                </div>
                <div class="border-t border-stone-100 pt-2 mt-2">
                    <div class="flex justify-between">
                        <span>Total Sewa</span>
                        <span>Rp {{ number_format($this->sewa->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya layanan</span>
                        <span>Rp {{ number_format($this->service_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pajak (10%)</span>
                        <span>Rp {{ number_format($this->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-stone-900 border-t border-stone-200 pt-2 mt-2">
                        <span>Total Pembayaran</span>
                        <span>Rp {{ number_format($this->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <form wire:submit="submit" class="space-y-4">
            <p class="text-sm font-medium text-stone-700">Pilih Metode Pembayaran</p>

            <label class="flex items-center gap-4 rounded-2xl border border-stone-200 bg-white p-4 cursor-pointer transition has-[:checked]:border-[#a67f71] has-[:checked]:ring-2 has-[:checked]:ring-[#a67f71]/20">
                <input type="radio" name="metode" value="QRIS" wire:model="metode" class="peer/qris accent-[#a67f71]">
                <div class="flex items-center gap-3">
                    <svg class="size-6 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    <div>
                        <p class="font-medium text-stone-900">QRIS</p>
                        <p class="text-xs text-stone-500">Semua Bank & E-Wallet</p>
                    </div>
                </div>
            </label>

            <label class="flex items-center gap-4 rounded-2xl border border-stone-200 bg-white p-4 cursor-pointer transition has-[:checked]:border-[#a67f71] has-[:checked]:ring-2 has-[:checked]:ring-[#a67f71]/20">
                <input type="radio" name="metode" value="Transfer BCA" wire:model="metode" class="peer/bca accent-[#a67f71]">
                <div class="flex items-center gap-3">
                    <svg class="size-6 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <div>
                        <p class="font-medium text-stone-900">Transfer BCA</p>
                        <p class="text-xs text-stone-500">Bank Transfer</p>
                    </div>
                </div>
            </label>

            <label class="flex items-center gap-4 rounded-2xl border border-stone-200 bg-white p-4 cursor-pointer transition has-[:checked]:border-[#a67f71] has-[:checked]:ring-2 has-[:checked]:ring-[#a67f71]/20">
                <input type="radio" name="metode" value="PayPal" wire:model="metode" class="peer/pp accent-[#a67f71]">
                <div class="flex items-center gap-3">
                    <svg class="size-6 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <div>
                        <p class="font-medium text-stone-900">PayPal</p>
                        <p class="text-xs text-stone-500">Pembayaran Internasional</p>
                    </div>
                </div>
            </label>

            @error('metode') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

            <button type="submit" class="w-full rounded-2xl bg-[#a67f71] py-3 text-white font-semibold hover:opacity-90 transition">
                Konfirmasi Pembayaran
            </button>
        </form>
    </div>
</div>
