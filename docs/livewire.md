# Panduan Livewire — Nginapin

## Apa itu Livewire?

Liveware = **PHP class + Blade view** jadi satu komponen interaktif. Klik tombol → jalanin PHP di server → view auto-update. Tanpa perlu nulis JavaScript.

---

## 1. Struktur Komponen

Setiap komponen punya 2 file:

```
app/Livewire/Properti/PropertiDetail.php    ← logic PHP
resources/views/livewire/properti/properti-detail.blade.php  ← HTML view
```

---

## 2. Property — data yang bisa diakses dari Blade

```php
// app/Livewire/Properti/PropertiDetail.php

class PropertiDetail extends Component
{
    public Properti $properti;  // model binding
    public int $months = 2;     // number, default 2
}
```

Di Blade tinggal pake:

```blade
<h1>{{ $properti->nama_properti }}</h1>
<span>{{ $months }} Bulan</span>
```

**Bedanya dengan passing data biasa:** kalau `$months` berubah di PHP, HTML langsung update otomatis (tanpa reload).

---

## 3. Action — method yang dipanggil dari tombol

```php
public function incrementMonths()
{
    $this->months++;
}

public function decrementMonths()
{
    $this->months = max(2, $this->months - 1);
}
```

Di Blade pake `wire:click`:

```blade
<button wire:click="incrementMonths">+</button>
<button wire:click="decrementMonths">-</button>
```

Pas diklik → `$months` berubah → semua tempat yg pake `{{ $months }}` di view ikut berubah.

Contoh real dari `properti-detail.blade.php`:

```blade
<button wire:click="decrementMonths" @if ($months <= 2) disabled @endif>
    -
</button>
<span>{{ $months }} Bulan</span>
<button wire:click="incrementMonths">
    +
</button>
```

---

## 4. Computed — property yg nilainya dihitung otomatis

```php
#[Computed]
public function totalHarga(): float
{
    return $this->properti->harga_per_dua_bulan + $this->extra_months * $this->properti->harga_per_bulan;
}

#[Computed]
public function pemeliharaan(): int
{
    return (int) ceil($this->total_harga * 0.05);
}
```

Di Blade pake nama snake_case:

```blade
<div>Total: Rp {{ number_format($this->total_harga, 0, ',', '.') }}</div>
<div>Pemeliharaan: Rp {{ number_format($this->pemeliharaan, 0, ',', '.') }}</div>
```

**Reaktif:** pas user klik `incrementMonths` (ubah `$months`), `$this->total_harga`, `$this->pemeliharaan`, `$this->biaya_layanan` ikut berubah otomatis.

---

## 5. Loading State — tombol disable selagi proses

```blade
<button wire:click="submit" wire:loading.attr="disabled">
    <span wire:loading.remove>Bayar via Midtrans</span>
    <span wire:loading>Memproses…</span>
</button>
```

| Attribute | Efek |
|-----------|------|
| `wire:loading.attr="disabled"` | Tambah `disabled` ke tombol |
| `wire:loading` | Muncul cuma pas loading |
| `wire:loading.remove` | Hilang pas loading |

Jadi: loading → tombol disable, teks ganti jadi "Memproses…". Selesai → tombol aktif lagi, teks balik "Bayar via Midtrans".

---

## 6. Navigasi — pindah halaman tanpa reload penuh

```blade
<a wire:navigate href="/properti">Kembali</a>
```

`wire:navigate` = pindah halaman pake AJAX. Lebih cepat, mirip SPA.

---

## 7. Flash Message — notifikasi setelah action

```php
public function book()
{
    // ...
    return $this->redirect('/pembayaran', navigate: true);
}

public function submit()
{
    // ...
    session()->flash('error', 'Gagal terhubung ke Midtrans: '.$e->getMessage());
}
```

Di Blade tampilin flash:

```blade
@if (session('error'))
    <div class="...">{{ session('error') }}</div>
@endif
```

`redirect` = pindah halaman. `session()->flash` = kasih pesan error tanpa pindah.

---

## 8. Layout — template otomatis

```php
#[Layout('layouts.app')]
class PropertiDetail extends Component
{
```

Semua komponen pake `#[Layout('layouts.app')]` — otomatis di-render di dalam `resources/views/layouts/app.blade.php`.

---

## 9. Mount — jalan pas komponen pertama kali di-load

```php
public function mount(string $id)
{
    $this->properti = Properti::with(['foto', 'unit', 'pemilik'])->findOrFail($id);
}
```

Parameter `$id` dikirim dari route:

```php
Route::get('/properti/{id}', PropertiDetail::class);
```

---

## 10. Pagination — daftar properti

```php
class PropertiList extends Component
{
    use WithPagination;

    public int $perPage = 6;

    public function properti()
    {
        return Properti::with('foto')->orderBy('nama_properti')->paginate($this->perPage);
    }
}
```

Di Blade:

```blade
@foreach ($properti as $p)
    ...
@endforeach

{{ $properti->links() }}  {{-- paging buttons --}}
```

---

## Ringkasan semua `wire:*` yang dipakai di proyek

| Attribute | Lokasi | Fungsi |
|-----------|--------|--------|
| `wire:click="submit"` | `pembayaran-form` | Klik tombol → panggil `submit()` |
| `wire:click="book"` | `properti-detail` | Klik → panggil `book()` |
| `wire:click="incrementMonths"` | `properti-detail` | Tambah durasi sewa |
| `wire:click="decrementMonths"` | `properti-detail` | Kurang durasi sewa (min 2) |
| `wire:loading.attr="disabled"` | `pembayaran-form` | Disable tombol pas loading |
| `wire:loading` | `pembayaran-form` | Muncul "Memproses…" |
| `wire:loading.remove` | `pembayaran-form` | Hilang teks asli pas loading |
| `wire:navigate` | `properti-detail`, `pembayaran-form` | Navigasi tanpa reload penuh |

## Flow Realnya

```
User buka /properti/{id}
        ↓
PropertiDetail di-load → mount($id) ambil data properti dari DB
        ↓
User atur durasi (2-24 bulan) → wire:click panggil incrementMonths/decrementMonths
        ↓
Tiap kali $months berubah → totalHarga, serviceFee, pemeliharaan, grandTotal auto hitung ulang
        ↓
User klik "Sewa Sekarang" → wire:click panggil book()
        ↓
book() simpan session → $this->redirect('/pembayaran')
        ↓
PembayaranForm tampilkan detail booking → user klik "Bayar via Midtrans"
        ↓
wire:click panggil submit() → loading state aktif → Snap API → redirect ke Midtrans
```
