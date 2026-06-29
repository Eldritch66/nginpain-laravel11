<div>
    <section class="w-full lg:max-w-[1750px] mx-auto px-4 pt-8 pb-2">
        <h1 class="text-3xl font-extrabold tracking-tight text-neutral-900">Temukan hunian yang tepat di Bogor.</h1>
        <p class="mt-1 text-sm text-neutral-500">Kosan & kontrakan sederhana, transparan, dan nyaman.</p>
    </section>

    <section class="w-full lg:max-w-[1750px] mx-auto mt-8 mb-16 px-4">
        <div wire:loading.flex wire:target="gotoPage,previousPage,nextPage" class="items-center justify-center py-4 mb-2">
            <svg class="size-8 animate-spin text-orange-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
        </div>

        <div wire:loading.class="opacity-40 pointer-events-none" wire:target="gotoPage,previousPage,nextPage" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 transition-opacity duration-300">
            @forelse ($properti as $p)
                <a wire:navigate href="/properti/{{ $p->id }}" class="group overflow-hidden rounded-2xl bg-white shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="relative aspect-[4/3] w-full overflow-hidden bg-neutral-100 rounded-t-2xl">
                        @if ($p->foto->isNotEmpty())
                            <img src="{{ $p->foto->first()->url }}" alt="{{ $p->nama_properti }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105 {{ $p->isOccupied ? 'opacity-60' : '' }}">
                        @else
                            <div class="flex h-full items-center justify-center text-neutral-300">
                                <svg class="size-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        @if ($p->isOccupied)
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="rounded-full bg-red-600 px-3 py-1 text-xs font-semibold tracking-wide text-white shadow-lg">Terisi</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <div class="flex items-center gap-2">
                            <h2 class="font-semibold text-lg text-neutral-900 line-clamp-1">{{ $p->nama_properti }}</h2>
                        </div>
                        <p class="text-sm text-neutral-500 mt-0.5">{{ $p->kota }}</p>

                        <p class="mt-3 text-orange-600 font-bold text-lg leading-none">
                            Rp {{ number_format($p->harga_per_dua_bulan, 0, ',', '.') }}
                            <span class="text-neutral-400 font-normal text-sm">/ 2 bulan</span>
                        </p>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-16 text-neutral-400">
                    <p class="text-lg">Tidak ada properti ditemukan.</p>
                </div>
            @endforelse
        </div>
    </section>

    <div class="w-full lg:max-w-[1750px] mx-auto mb-16 px-4">
        @if ($properti->hasPages())
            <div class="flex justify-center gap-2">
                @if ($properti->onFirstPage())
                    <span class="rounded-lg border border-neutral-200 px-3 py-2 text-sm text-neutral-300">Previous</span>
                @else
                    <button wire:click="previousPage" class="rounded-lg border border-neutral-300 px-3 py-2 text-sm hover:bg-neutral-50">Previous</button>
                @endif

                @foreach ($properti->getUrlRange(1, $properti->lastPage()) as $page => $url)
                    <button wire:click="gotoPage({{ $page }})" class="rounded-lg border px-3 py-2 text-sm {{ $page === $properti->currentPage() ? 'bg-orange-600 text-white border-orange-600' : 'border-neutral-300 hover:bg-neutral-50' }}">
                        {{ $page }}
                    </button>
                @endforeach

                @if ($properti->onLastPage())
                    <span class="rounded-lg border border-neutral-200 px-3 py-2 text-sm text-neutral-300">Next</span>
                @else
                    <button wire:click="nextPage" class="rounded-lg border border-neutral-300 px-3 py-2 text-sm hover:bg-neutral-50">Next</button>
                @endif
            </div>
        @endif
    </div>
</div>
