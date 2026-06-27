<form wire:submit="submit" class="flex flex-col gap-5">
    <div class="rounded-xl border-2 border-neutral-900 bg-white p-6 shadow-[5px_5px_0px_#292524] md:p-8">
        <div class="mb-6 flex items-center gap-2">
            <span class="inline-flex -rotate-2 items-center gap-1.5 rounded-md border-2 border-orange-300 bg-orange-100 px-3 py-1 text-xs font-bold uppercase text-orange-700 shadow-[2px_2px_0px_#ea580c]">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                Ada yang bisa dibantu?
            </span>
        </div>

        <div class="space-y-1.5">
            <label for="judul" class="text-sm font-bold uppercase tracking-wide text-neutral-800">Judul</label>
            <input id="judul" wire:model="judul" type="text" placeholder="Misal: Kendala saat booking properti"
                class="h-11 w-full border-2 border-neutral-900 bg-white px-4 text-neutral-900 shadow-[3px_3px_0px_#292524] transition-all placeholder:text-neutral-400 focus-visible:shadow-[5px_5px_0px_#292524] focus-visible:border-orange-500 rounded-lg">
            @error('judul') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mt-5 space-y-1.5">
            <label for="kategori" class="text-sm font-bold uppercase tracking-wide text-neutral-800">Kategori</label>
            <select id="kategori" wire:model="kategori" required
                class="h-11 w-full border-2 border-neutral-900 bg-white px-4 text-neutral-900 shadow-[3px_3px_0px_#292524] transition-all focus-visible:shadow-[5px_5px_0px_#292524] focus-visible:border-orange-500 rounded-lg">
                <option value="">Pilih kategori</option>
                <option value="teknis">Teknis</option>
                <option value="pembayaran">Pembayaran</option>
                <option value="properti">Properti</option>
                <option value="akun">Akun</option>
                <option value="lainnya">Lainnya</option>
            </select>
            @error('kategori') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mt-5 space-y-1.5">
            <label for="pesan" class="text-sm font-bold uppercase tracking-wide text-neutral-800">Pesan</label>
            <textarea id="pesan" wire:model="pesan" placeholder="Jelaskan keluhan atau pertanyaanmu secara detail..."
                class="min-h-[120px] w-full border-2 border-neutral-900 bg-white px-4 py-3 text-neutral-900 shadow-[3px_3px_0px_#292524] transition-all placeholder:text-neutral-400 focus-visible:shadow-[5px_5px_0px_#292524] focus-visible:border-orange-500 rounded-lg resize-y"></textarea>
            @error('pesan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        @if ($error)
            <div class="mt-4 flex items-center gap-2 rounded-lg border-2 border-red-500 bg-red-50 px-4 py-3 text-sm font-medium text-red-600 shadow-[2px_2px_0px_#dc2626]">
                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $error }}</span>
            </div>
        @endif

        @if ($success)
            <div class="mt-4 flex items-center gap-2 rounded-lg border-2 border-emerald-500 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-600 shadow-[2px_2px_0px_#059669]">
                <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Tiket berhasil dikirim! Tim kami akan menghubungi kamu segera.</span>
            </div>
        @endif

        <button type="submit"
            class="group relative mt-6 h-12 w-full overflow-hidden rounded-lg border-2 border-neutral-900 bg-orange-500 font-bold uppercase tracking-wider text-white shadow-[4px_4px_0px_#292524] transition-all hover:-translate-y-0.5 hover:shadow-[6px_6px_0px_#292524] active:translate-y-0.5 active:shadow-[2px_2px_0px_#292524] cursor-pointer">
            <span class="flex items-center justify-center gap-2">
                <svg class="size-4 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Kirim Tiket Bantuan
            </span>
        </button>
    </div>
</form>
