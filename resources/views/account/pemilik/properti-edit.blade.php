@extends('layouts.account')

@section('title', 'Edit Properti | Nginapin')

@section('content')
<a wire:navigate href="/account/pemilik/properti/{{ $properti->id }}" class="inline-flex items-center gap-2 text-sm text-stone-500 hover:text-stone-700 mb-6">
    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali
</a>

<h2 class="font-semibold text-2xl text-stone-900 mb-7">Edit Properti</h2>

<form method="POST" action="/account/pemilik/properti/{{ $properti->id }}/edit" enctype="multipart/form-data" class="rounded-2xl border border-stone-200 bg-white p-6 sm:p-8 space-y-5">
    @csrf

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">Nama Properti</label>
        <input type="text" name="nama_properti" value="{{ old('nama_properti', $properti->nama_properti) }}" required class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
        @error('nama_properti') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">Tipe</label>
        <select name="tipe" required class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
            <option value="kost" {{ $properti->tipe === 'kost' ? 'selected' : '' }}>Kos</option>
            <option value="kontrakan" {{ $properti->tipe === 'kontrakan' ? 'selected' : '' }}>Kontrakan</option>
        </select>
        @error('tipe') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">Alamat</label>
        <textarea name="alamat" required rows="3" class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">{{ old('alamat', $properti->alamat) }}</textarea>
        @error('alamat') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">Harga per Bulan</label>
        <input type="number" name="harga_per_bulan" value="{{ old('harga_per_bulan', $properti->harga_per_bulan) }}" min="0" required class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
        @error('harga_per_bulan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">Luas Bangunan (m²)</label>
        <input type="number" name="luas_bangunan" value="{{ old('luas_bangunan', $properti->unit->luas_bangunan) }}" min="0" class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
        @error('luas_bangunan') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Kamar Tidur</label>
            <input type="number" name="jumlah_kamar_tidur" value="{{ old('jumlah_kamar_tidur', $properti->unit->jumlah_kamar_tidur ?? 1) }}" min="1" required class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
            @error('jumlah_kamar_tidur') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Kamar Mandi</label>
            <input type="number" name="jumlah_kamar_mandi" value="{{ old('jumlah_kamar_mandi', $properti->unit->jumlah_kamar_mandi ?? 1) }}" min="1" required class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
            @error('jumlah_kamar_mandi') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Kapasitas Penghuni</label>
            <input type="number" name="kapasitas_penghuni" value="{{ old('kapasitas_penghuni', $properti->unit->kapasitas_penghuni ?? 1) }}" min="1" required class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
            @error('kapasitas_penghuni') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Lantai</label>
            <input type="number" name="lantai" value="{{ old('lantai', $properti->unit->lantai ?? 1) }}" min="1" required class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
            @error('lantai') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">Keterangan</label>
        <textarea name="keterangan" rows="3" class="w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">{{ old('keterangan', $properti->unit->keterangan) }}</textarea>
    </div>

    @if ($properti->foto->isNotEmpty())
        <div>
            <p class="text-sm font-medium text-stone-700 mb-2">Foto Saat Ini</p>
            <img src="{{ $properti->foto->first()->url }}" class="h-32 w-32 rounded-xl object-cover border">
        </div>
    @endif

    <div>
        <label class="block text-sm font-medium text-stone-700 mb-1">{{ $properti->foto->isNotEmpty() ? 'Ganti Foto' : 'Foto' }}</label>
        <input type="file" name="foto" accept="image/*" class="w-full text-sm text-stone-500 file:mr-4 file:rounded-xl file:border-0 file:bg-[#a67f71] file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:opacity-90">
        @error('foto') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
    </div>

    <button type="submit" class="w-full rounded-xl bg-[#a67f71] py-3 text-white font-semibold hover:opacity-90 transition cursor-pointer">
        Simpan Perubahan
    </button>
</form>
@endsection
