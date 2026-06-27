<header class="flex px-4 py-5 justify-between items-center mx-auto w-full lg:max-w-[1750px] h-16 sticky top-0 z-50 bg-white shadow-sm rounded-none sm:rounded-t-none sm:rounded-b-lg">
    <div class="shrink-0">
        <a wire:navigate href="/">
            <img src="/logo.png" alt="Logo" class="w-34 h-auto object-contain block">
        </a>
    </div>

    <div class="hidden sm:flex flex-shrink-0">
        @auth
            <a wire:navigate href="/account" class="text-base font-extralight hover:text-orange-600 transition-colors">Dashboard</a>
        @else
            <div class="bg-orange-600 border-2 px-4 py-2 rounded-full text-white flex items-center gap-2">
                <a wire:navigate href="/login">Login</a>
                <svg class="size-9" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
        @endauth
    </div>

    <div class="flex sm:hidden">
        <div x-data="{ isOpen: false }" class="relative">
            <button @click="isOpen = !isOpen" :class="isOpen ? 'hidden' : 'flex'" class="flex items-center justify-center w-10 h-10 rounded-full bg-stone-100 hover:bg-stone-200 transition-colors duration-200" aria-label="Toggle menu">
                <svg class="size-5 text-stone-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <div x-show="isOpen" @click="isOpen = false" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-45 transition-opacity duration-300" style="display: none;"></div>

            <div x-show="isOpen" class="fixed top-0 left-0 right-0 z-50 bg-white shadow-xl transition-all duration-500" style="display: none;" x-transition:enter="transform transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]" x-transition:enter-start="-translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transform transition-all duration-500 ease-[cubic-bezier(0.16,1,0.3,1)]" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-full opacity-0">
                <div class="flex items-center justify-between px-6 py-5 border-b border-stone-100">
                    <a wire:navigate href="/">
                        <img src="/logo.png" alt="Logo" class="w-34 h-auto object-contain">
                    </a>
                    <button @click="isOpen = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-stone-100">
                        <svg class="size-4 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <nav class="px-6 py-6 flex flex-col gap-1">
                    <a wire:navigate href="/properti" @click="isOpen = false" class="group flex items-center justify-between py-4 border-b border-stone-100 text-2xl font-light text-stone-800 hover:text-stone-500 transition-all duration-300">
                        Properti
                        <span class="text-stone-300 group-hover:translate-x-1 transition-transform duration-200 text-lg">→</span>
                    </a>
                    <a wire:navigate href="/tentang" @click="isOpen = false" class="group flex items-center justify-between py-4 border-b border-stone-100 text-2xl font-light text-stone-800 hover:text-stone-500 transition-all duration-300">
                        Tentang Kami
                        <span class="text-stone-300 group-hover:translate-x-1 transition-transform duration-200 text-lg">→</span>
                    </a>
                </nav>

                <div class="px-6 py-6 flex flex-col gap-2">
                    @auth
                        <a wire:navigate href="/account" @click="isOpen = false" class="flex items-center gap-3 w-full px-5 py-3.5 rounded-full bg-stone-900 text-white text-sm font-medium hover:bg-stone-700 transition-colors duration-200">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                    @else
                        <a wire:navigate href="/login" @click="isOpen = false" class="flex items-center gap-3 w-full px-5 py-3.5 rounded-full bg-stone-900 text-white text-sm font-medium hover:bg-stone-700 transition-colors duration-200">
                            <svg class="size-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            Login to your account
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>
