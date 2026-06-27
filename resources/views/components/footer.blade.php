<footer class="mx-auto w-full lg:max-w-[1750px] border-t border-l border-r border-neutral-200 bg-white mt-auto px-4 py-3 md:px-8 lg:px-12 rounded-none sm:rounded-t-lg">
    <div class="flex flex-col items-center gap-1 sm:flex-row sm:justify-between">
        <div class="flex items-center gap-3">
            <a wire:navigate href="/">
                <img src="/logo.png" alt="Nginapin" class="h-7 w-auto">
            </a>
            <span class="hidden text-sm text-neutral-300 sm:inline">/</span>
            <p class="text-sm text-neutral-400">&copy; {{ date('Y') }} Nginapin.</p>
        </div>
        <nav class="flex gap-5 text-sm text-neutral-500">
            <a wire:navigate href="/properti" class="transition hover:text-orange-600">Properti</a>
            <a wire:navigate href="/tentang" class="transition hover:text-orange-600">Tentang Kami</a>
        </nav>
    </div>
</footer>
