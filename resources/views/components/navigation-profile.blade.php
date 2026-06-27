<nav class="flex flex-row sm:flex-col justify-between sm:justify-start border-b sm:border-b-0 sm:border-r pb-4 sm:pb-0 sm:h-full">
    <a wire:navigate href="/account/profile" class="hidden sm:flex items-center gap-3 px-4 py-3 mb-2 rounded-xl bg-stone-50 hover:bg-stone-100 transition group">
        <div class="size-10 shrink-0 rounded-full overflow-hidden border border-stone-200 bg-stone-100 flex items-center justify-center">
            @if (Auth::user()->avatar_url)
                <img src="{{ asset(Auth::user()->avatar_url) }}" alt="" class="size-full object-cover">
            @else
                <svg class="size-5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold text-stone-900 truncate group-hover:text-orange-600 transition">{{ Auth::user()->name }}</p>
            <p class="text-xs text-stone-400 truncate">{{ Auth::user()->email }}</p>
        </div>
    </a>
    <div class="flex flex-row sm:flex-col gap-3 sm:gap-1 w-full">
        <a wire:navigate href="/account" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-stone-700 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition">
            <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Home
        </a>
        @auth
            @if (Auth::user()->role === 'penyewa')
                <a wire:navigate href="/account/sewa" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-stone-700 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition">
                    <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Sewa Properti
                </a>
            @else
                <button type="button" x-data="{ showWarningSewa: false }" @click="showWarningSewa = true" class="flex items-center gap-3 w-full px-4 py-3 text-sm font-medium text-stone-400 hover:text-stone-400 rounded-xl transition cursor-not-allowed text-left">
                    <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Sewa Properti
                    <span class="ml-auto text-[10px] font-medium text-stone-400 border border-stone-300 rounded-full px-2 py-0.5">Penyewa</span>
                </button>

                <div x-show="showWarningSewa" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
                    <div class="absolute inset-0 bg-black/50" @click="showWarningSewa = false"></div>
                    <div class="relative bg-white p-6 shadow-xl border border-stone-200 w-full max-w-sm mx-4 rounded-2xl">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100">
                                <svg class="size-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-stone-900">Akses Terbatas</h3>
                        </div>
                        <p class="text-sm text-stone-600 mb-6">Halaman Sewa Properti hanya untuk pengguna dengan peran <strong>Penyewa</strong>. Gunakan akun penyewa untuk mengakses fitur ini.</p>
                        <button @click="showWarningSewa = false" class="w-full rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-stone-800 transition cursor-pointer">
                            Mengerti
                        </button>
                    </div>
                </div>
            @endif

            @if (Auth::user()->role === 'pemilik')
                <a wire:navigate href="/account/pemilik/properti" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-stone-700 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition">
                    <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Kelola Properti
                </a>
            @else
                <button type="button" x-data="{ showWarningKelola: false }" @click="showWarningKelola = true" class="flex items-center gap-3 w-full px-4 py-3 text-sm font-medium text-stone-400 hover:text-stone-400 rounded-xl transition cursor-not-allowed text-left">
                    <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Kelola Properti
                    <span class="ml-auto text-[10px] font-medium text-stone-400 border border-stone-300 rounded-full px-2 py-0.5">Pemilik</span>
                </button>

                <div x-show="showWarningKelola" class="fixed inset-0 z-50 flex items-center justify-center" style="display: none;">
                    <div class="absolute inset-0 bg-black/50" @click="showWarningKelola = false"></div>
                    <div class="relative bg-white p-6 shadow-xl border border-stone-200 w-full max-w-sm mx-4 rounded-2xl">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100">
                                <svg class="size-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            </div>
                            <h3 class="text-lg font-semibold text-stone-900">Akses Terbatas</h3>
                        </div>
                        <p class="text-sm text-stone-600 mb-6">Halaman Kelola Properti hanya untuk pengguna dengan peran <strong>Pemilik</strong>. Gunakan akun pemilik untuk mengakses fitur ini.</p>
                        <button @click="showWarningKelola = false" class="w-full rounded-xl bg-stone-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-stone-800 transition cursor-pointer">
                            Mengerti
                        </button>
                    </div>
                </div>
            @endif

            <a wire:navigate href="/account/tiket" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-stone-700 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition">
                <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                Tiket Bantuan
            </a>
        @endauth
    </div>
    <form method="POST" action="/logout" class="sm:mt-auto">
        @csrf
        <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition cursor-pointer">
            <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Sign Out
        </a>
    </form>
</nav>
