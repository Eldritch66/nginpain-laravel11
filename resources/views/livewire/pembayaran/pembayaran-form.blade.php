<div class="min-h-screen bg-stone-50">
    <div class="mx-auto max-w-2xl px-4 py-8">
        <h1 class="text-3xl font-semibold text-stone-900 mb-6">Pembayaran</h1>

        @php $booking = session('booking'); @endphp

        @if (! $booking)
            <div class="rounded-2xl border border-stone-200 bg-white p-8 text-center">
                <p class="text-stone-500">Tidak ada data booking. Silakan pilih properti terlebih dahulu.</p>
                <a wire:navigate href="/properti" class="inline-block mt-4 rounded-xl bg-[#a67f71] px-6 py-3 text-white font-semibold">Lihat Properti</a>
            </div>
        @else
            <div class="rounded-[28px] border border-stone-200 bg-white p-6 shadow-sm mb-6">
                <h2 class="text-xl font-semibold text-stone-900 mb-4">{{ $booking['properti_nama'] }}</h2>
                <div class="space-y-2 text-sm text-stone-600">
                    <div class="flex justify-between">
                        <span>Tanggal Sewa</span>
                        <span>{{ \Carbon\Carbon::parse($booking['start_date'])->locale('id')->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($booking['end_date'])->locale('id')->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Durasi</span>
                        <span>{{ $booking['months'] }} bulan</span>
                    </div>
                    <div class="border-t border-stone-100 pt-2 mt-2">
                        <div class="flex justify-between">
                            <span>Total Sewa</span>
                            <span>Rp {{ number_format((int) $booking['total_harga'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya layanan</span>
                            <span>Rp {{ number_format((int) $booking['service_fee'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Pemeliharaan (5%)</span>
                            <span>Rp {{ number_format((int) $booking['pemeliharaan'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-semibold text-stone-900 border-t border-stone-200 pt-2 mt-2">
                            <span>Total Pembayaran</span>
                            <span>Rp {{ number_format((int) $booking['grand_total'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" wire:click="submit" wire:loading.attr="disabled" class="w-full rounded-2xl bg-[#a67f71] py-3 text-white font-semibold hover:opacity-90 transition disabled:opacity-50">
                <span wire:loading.remove>Bayar via Midtrans</span>
                <span wire:loading>Memproses…</span>
            </button>
        @endif
    </div>
</div>
