<div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-white via-orange-50/30 to-neutral-50">
    <div class="w-full max-w-md mx-4">
        <div class="rounded-xl border border-neutral-200 bg-white p-8 shadow-sm">
            <h1 class="text-2xl font-bold text-center text-neutral-900">Daftar</h1>
            <p class="mt-2 text-sm text-center text-neutral-500">Buat akun baru</p>

            @if ($error)
                <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">{{ $error }}</div>
            @endif

            @if (session('success'))
                <div class="mt-4 rounded-lg bg-green-50 p-3 text-sm text-green-600">{{ session('success') }}</div>
            @endif

            <form wire:submit="register" class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700">Nama</label>
                    <input type="text" wire:model="name" class="mt-1 block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700">Email</label>
                    <input type="email" wire:model="email" class="mt-1 block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700">Password</label>
                    <input type="password" wire:model="password" class="mt-1 block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700">Konfirmasi Password</label>
                    <input type="password" wire:model="confirmPassword" class="mt-1 block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm focus:border-orange-500 focus:ring-1 focus:ring-orange-500">
                    @error('confirmPassword') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full rounded-xl bg-orange-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-orange-700 transition">
                    Daftar
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-neutral-500">
                Sudah punya akun? <a wire:navigate href="/login" class="font-medium text-orange-600 hover:text-orange-700">Masuk</a>
            </p>
        </div>
    </div>
</div>
