@extends('layouts.account')

@section('title', 'Profil — Nginapin')

@section('content')
<a wire:navigate href="/account" class="inline-flex items-center gap-2 text-sm text-stone-500 hover:text-stone-700 mb-6">
    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali
</a>

<h2 class="font-semibold text-2xl text-stone-900 mb-7">Profil</h2>

{{-- Form Informasi Profil --}}
<form method="POST" action="/account/profile" enctype="multipart/form-data" class="rounded-2xl border border-stone-200 bg-white p-6 sm:p-8 space-y-5 mb-8">
    @csrf

    <h3 class="text-lg font-semibold text-stone-900">Informasi Profil</h3>

    <div x-data="{ preview: null }">
        <label class="block text-sm font-medium text-stone-700 mb-2">Foto Profil</label>
        <div class="flex items-center gap-4">
            <div class="relative size-20 shrink-0 rounded-full overflow-hidden border border-stone-200 bg-stone-100">
                <img
                    x-show="!preview"
                    src="{{ $user->avatar_url ? asset($user->avatar_url) : '' }}"
                    alt=""
                    class="size-full object-cover"
                    x-ref="currentAvatar"
                >
                <div
                    x-show="!preview && !{{ $user->avatar_url ? 'true' : 'false' }}"
                    class="size-full flex items-center justify-center text-stone-400 text-xl font-semibold"
                >
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <template x-if="preview">
                    <img :src="preview" alt="" class="size-full object-cover">
                </template>
            </div>
            <div>
                <label class="cursor-pointer inline-flex items-center gap-2 rounded-xl border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Ganti Foto
                    <input type="file" name="avatar" accept="image/*" class="hidden" @change="preview = URL.createObjectURL($el.files[0])">
                </label>
                <p class="text-xs text-stone-400 mt-1">JPEG, PNG. Maks 2MB.</p>
            </div>
        </div>
        @error('avatar') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
        @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">Email</label>
        <div class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 text-sm text-stone-500 flex items-center gap-2">
            <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            {{ $user->email }}
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">No. Handphone</label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="08xxxxxxxxxx" class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
        @error('no_hp') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="w-full sm:w-auto rounded-xl bg-[#a67f71] px-8 py-2.5 text-sm font-medium text-white hover:opacity-90 transition cursor-pointer">
        Simpan Perubahan
    </button>
</form>

{{-- Form Ubah Password --}}
@if ($user->password)
    <form method="POST" action="/account/profile" class="rounded-2xl border border-stone-200 bg-white p-6 sm:p-8 space-y-5">
        @csrf

        <h3 class="text-lg font-semibold text-stone-900">Ubah Password</h3>

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Password Saat Ini</label>
            <input type="password" name="current_password" required class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
            @error('current_password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Password Baru</label>
            <input type="password" name="new_password" required min="6" class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
            @error('new_password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" required min="6" class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
        </div>

        <button type="submit" class="w-full sm:w-auto rounded-xl bg-stone-900 px-8 py-2.5 text-sm font-medium text-white hover:bg-stone-800 transition cursor-pointer">
            Ubah Password
        </button>
    </form>
@endif
@endsection
