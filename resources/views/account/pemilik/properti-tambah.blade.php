@extends('layouts.account')

@section('title', 'Tambah Properti | Nginapin')

@section('content')
<a wire:navigate href="/account/pemilik/properti" class="inline-flex items-center gap-2 text-sm text-stone-500 hover:text-stone-700 mb-6">
    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    Kembali
</a>

<h1 class="text-2xl font-semibold text-stone-900 mb-6">Tambah Properti</h1>

<form method="POST" action="/account/pemilik/properti/tambah" enctype="multipart/form-data" class="space-y-5 max-w-2xl">
    @csrf
    <div>
        <label class="block text-sm font-medium text-stone-700">Nama Properti</label>
        <input type="text" name="nama_properti" value="{{ old('nama_properti') }}" required class="mt-1 block w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
        @error('nama_properti') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-stone-700">Tipe</label>
            <select name="tipe" required class="mt-1 block w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
                <option value="kost" {{ old('tipe') === 'kost' ? 'selected' : '' }}>Kos</option>
                <option value="kontrakan" {{ old('tipe') === 'kontrakan' ? 'selected' : '' }}>Kontrakan</option>
            </select>
            @error('tipe') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700">Harga per Bulan (Rp)</label>
            <input type="number" name="harga_per_bulan" value="{{ old('harga_per_bulan') }}" required min="0" class="mt-1 block w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
            @error('harga_per_bulan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700">Alamat</label>
        <textarea name="alamat" required rows="2" class="mt-1 block w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">{{ old('alamat') }}</textarea>
        @error('alamat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-stone-700">Luas Bangunan (m²)</label>
            <input type="number" name="luas_bangunan" value="{{ old('luas_bangunan') }}" step="0.01" min="0" class="mt-1 block w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700">Kamar Tidur</label>
            <input type="number" name="jumlah_kamar_tidur" value="{{ old('jumlah_kamar_tidur', 1) }}" required min="1" class="mt-1 block w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
            @error('jumlah_kamar_tidur') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700">Kamar Mandi</label>
            <input type="number" name="jumlah_kamar_mandi" value="{{ old('jumlah_kamar_mandi', 1) }}" required min="1" class="mt-1 block w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
            @error('jumlah_kamar_mandi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700">Kapasitas Penghuni</label>
            <input type="number" name="kapasitas_penghuni" value="{{ old('kapasitas_penghuni', 1) }}" required min="1" class="mt-1 block w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
        </div>
        <div>
            <label class="block text-sm font-medium text-stone-700">Lantai</label>
            <input type="number" name="lantai" value="{{ old('lantai', 1) }}" required min="1" class="mt-1 block w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700">Keterangan</label>
        <textarea name="keterangan" rows="2" class="mt-1 block w-full rounded-xl border border-stone-300 px-4 py-2.5 text-sm focus:border-[#a67f71] focus:ring-1 focus:ring-[#a67f71]">{{ old('keterangan') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-stone-700">Foto Properti</label>
        <input type="file" name="foto" accept="image/*" class="mt-1 block w-full text-sm text-stone-500 file:mr-4 file:rounded-xl file:border-0 file:bg-stone-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-stone-700 hover:file:bg-stone-200">
    </div>

    <button type="submit" class="rounded-xl bg-[#a67f71] px-6 py-2.5 text-sm font-medium text-white hover:opacity-90 transition cursor-pointer">
        Simpan Properti
    </button>
</form>
@endsection
