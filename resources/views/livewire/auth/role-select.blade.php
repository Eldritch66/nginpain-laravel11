<div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-white via-orange-50/30 to-neutral-50">
    <div class="w-full max-w-lg mx-4">
        <div class="rounded-xl border border-neutral-200 bg-white p-8 shadow-sm text-center">
            <h1 class="text-2xl font-bold text-neutral-900">Pilih Role Anda</h1>
            <p class="mt-2 text-sm text-neutral-500">Apakah Anda ingin bergabung sebagai?</p>

            @if ($error)
                <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">{{ $error }}</div>
            @endif

            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <button wire:click="selectRole('penyewa')" class="rounded-xl border-2 border-orange-200 bg-orange-50 p-6 text-center hover:border-orange-400 transition">
                    <span class="text-4xl">👤</span>
                    <h2 class="mt-3 text-lg font-semibold text-neutral-900">Penyewa</h2>
                    <p class="mt-1 text-sm text-neutral-500">Mencari kosan atau kontrakan</p>
                </button>

                <button wire:click="selectRole('pemilik')" class="rounded-xl border-2 border-blue-200 bg-blue-50 p-6 text-center hover:border-blue-400 transition">
                    <span class="text-4xl">🏠</span>
                    <h2 class="mt-3 text-lg font-semibold text-neutral-900">Pemilik</h2>
                    <p class="mt-1 text-sm text-neutral-500">Menyewakan properti</p>
                </button>
            </div>
        </div>
    </div>
</div>
