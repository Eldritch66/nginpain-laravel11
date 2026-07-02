# Livewire vs Blade — dokumentasi

> Proyek ini pakai **Livewire 3** (full-stack framework Laravel) + **Blade** (template engine).

---

## Blade (biasa)

### Apa itu
Blade adalah **template engine** Laravel — ngubah file `.blade.php` jadi HTML. Tidak ada komunikasi ke server setelah halaman dikirim ke browser.

### Cara kerja di proyek ini

**Route** (`routes/web.php` baris 23-25):
```php
Route::get('/', function () {
    return view('beranda');
});
```

**View** (`resources/views/beranda.blade.php`):
```blade
@extends('layouts.app')
@section('content')
    <h1>Selamat datang</h1>
@endsection
```

**Layout** (`resources/views/layouts/app.blade.php`):
```blade
<main>
    @yield('content')
</main>
```

### Jika butuh interaksi (form submit, dll) — Blade saja:

1. Buat route POST
2. Buat controller / closure
3. Validasi data (`request()->validate(...)`)
4. Simpan ke DB
5. Redirect (reload halaman)

Contoh di proyek ini (`routes/web.php` baris 256-307):
```php
Route::post('/account/pemilik/properti/tambah', function () {
    $data = request()->validate([...]);
    Properti::create($data);
    return redirect('/account/pemilik/properti')->with('success', '...');
});
```

→ **Halaman reload setiap kali** ada interaksi.

---

## Livewire

### Apa itu
Livewire adalah **full-stack framework** yang bikin halaman jadi **reaktif** — interaksi user dikirim via AJAX ke server, server proses, dan **cuma bagian HTML yang berubah** yang dikirim balik. **Tanpa reload halaman**, **tanpa nulis JavaScript**.

### Cara kerja di proyek ini

**Route** (`routes/web.php` baris 69):
```php
Route::get('/properti', PropertiList::class);
```

**Class** (`app/Livewire/Properti/PropertiList.php`):
```php
#[Layout('layouts.app')]
class PropertiList extends Component
{
    use WithPagination;

    public int $perPage = 6;

    #[Computed]
    public function properti()
    {
        return Properti::with('foto')
            ->orderBy('nama_properti')
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.properti.properti-list', [
            'properti' => $this->properti,
        ]);
    }
}
```

**View** (`resources/views/livewire/properti/properti-list.blade.php`):
```blade
<div>
    @forelse ($properti as $p)
        <a wire:navigate href="/properti/{{ $p->id }}">...</a>
    @empty
        ...
    @endforelse

    <button wire:click="previousPage">Previous</button>
    <button wire:click="nextPage">Next</button>
</div>
```

### Alur Reaktif:

```
User klik "Next"
    ↓
Livewire JS tangkap event
    ↓
AJAX (fetch) → Laravel → method nextPage()
    ↓
PropertiList class di-render ulang (state $perPage tetap)
    ↓
View Blade di-render dengan data baru
    ↓
HTML baru (cuma bagian yg berubah) dikirim balik
    ↓
Livewire JS update DOM — tanpa reload halaman
```

---

## Perbandingan langsung

| Aspek | Blade biasa | Livewire |
|-------|-------------|----------|
| **Template** | Blade (.blade.php) | Blade (.blade.php) — sama |
| **Logic** | Controller / Closure | Class komponen (`app/Livewire/`) |
| **Interaksi** | `form submit` → reload page | `wire:click`, `wire:submit` → AJAX |
| **State** | Hilang setelah request | `public $count` tetap ada antar request |
| **Validasi** | `request()->validate()` | `$this->validate()` (sama, beda konteks) |
| **Reload halaman** | Ya, setiap interaksi | Tidak pernah |
| **JavaScript** | Manual (fetch/axios) | Nol (cuma pasang `@livewireScripts`) |
| **Pagination** | Manual | Trait `WithPagination` — otomatis |
| **Data binding** | `value="{{ $foo }}"` | `wire:model="foo"` — 2 arah |
| **Real-time** | Harus polling / JS manual | `wire:model.live` otomatis sync ke server |
| **Cocok untuk** | Halaman statis, CRUD sederhana | Dashboard, filter, pagination, form multi-step |

---

## Directive Livewire yang sering dipakai

### `wire:click="method"`
Jalankan method pas diklik:
```blade
<button wire:click="increment">+</button>
```

### `wire:submit="method"`
Jalankan method pas form disubmit:
```blade
<form wire:submit="save">...</form>
```

### `wire:model="property"`
Data binding 2 arah — input otomatis sync ke property:
```blade
<input type="text" wire:model="title">
```
Tambahkan `.live` biar real-time (update tiap kali user ngetik):
```blade
<input type="text" wire:model.live="search">
```

### `wire:navigate`
Navigasi antar halaman Livewire tanpa reload (seperti SPA):
```blade
<a wire:navigate href="/properti/{{ $id }}">Detail</a>
```

### `wire:key`
Wajib ada di loop `@foreach` biar Livewire bisa track elemen:
```blade
@foreach ($items as $item)
    <div wire:key="{{ $item->id }}">...</div>
@endforeach
```

### `wire:loading.class`
Tambah/remove class CSS saat loading:
```blade
<div wire:loading.class="opacity-50">Loading...</div>
```

---

## Lifecycle hooks — method yang bisa dipakai di class komponen

| Method | Kapan dijalankan |
|--------|-----------------|
| `mount()` | Saat komponen pertama kali dibuat |
| `boot()` | Setiap request (awal dan berikutnya) |
| `updating($property, $value)` | Sebelum property diupdate |
| `updated($property, $value)` | Setelah property diupdate |
| `render()` | Setiap kali komponen di-render |
| `rendered()` | Setelah render selesai |

---

## Kapan pake yang mana?

**Pake Blade biasa** kalau:
- Halaman statis (beranda, tentang, halaman login sederhana)
- Form yang cukup di-submit dan redirect biasa
- Tidak butuh feedback real-time

**Pake Livewire** kalau:
- Ada interaksi tanpa reload (pagination, filter, search)
- Form multi-step
- Butuh real-time update
- Data berubah berdasarkan input user (dashboard, laporan)
- Ingin mengurangi / menghilangkan JavaScript manual

---

## Struktur file

```
app/Livewire/                    ← Class komponen (logic PHP)
├── Auth/
│   ├── Register.php
│   └── RoleSelect.php
├── Pembayaran/
│   └── PembayaranForm.php
├── Properti/
│   ├── PropertiList.php         ← #[\Livewire\Attributes\Layout('layouts.app')]
│   └── PropertiDetail.php
└── Tiket/
    └── FormBantuan.php

resources/views/livewire/         ← View Blade (template HTML)
├── auth/
│   ├── register.blade.php
│   └── role-select.blade.php
├── pembayaran/
│   └── pembayaran-form.blade.php
├── properti/
│   ├── properti-list.blade.php   ← Dipanggil dari PropertiList::render()
│   └── properti-detail.blade.php
└── tiket/
    └── form-bantuan.blade.php
```

---

## Referensi

- Dokumentasi resmi Livewire: https://livewire.laravel.com/docs/3.x/quickstart
- GitHub Livewire: https://github.com/livewire/livewire
