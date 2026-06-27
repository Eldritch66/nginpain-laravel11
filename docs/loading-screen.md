# Loading Screen di Halaman Properti

## Lokasi

**File**: `resources/views/livewire/properti/properti-list.blade.php:8-10`

## Kode

```blade
<div wire:loading.flex wire:target="gotoPage,previousPage,nextPage" class="items-center justify-center py-4 mb-2">
    <svg class="size-8 animate-spin text-orange-600" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>
</div>
```

---

## Cara Kerja

### 1. `wire:loading.flex`

Ini adalah fitur **Livewire** — bukan Alpine.js, bukan JavaScript manual, murni bawaan Livewire.

Secara default, elemen dengan `wire:loading.flex` memiliki `display: none`. Saat Livewire mengirim request AJAX ke server (misalnya karena tombol diklik), elemen ini otomatis berubah menjadi `display: flex`. Setelah response diterima dan DOM diupdate, elemen kembali ke `display: none`.

| State | Display |
|---|---|
| Tidak ada request | `none` (tersembunyi) |
| Ada request AJAX | `flex` (muncul) |

Tanpa `.flex` di belakang `wire:loading`, Livewire menggunakan `display: block` saat muncul. `.flex` membuatnya menggunakan `display: flex`.

Variasi lain: `wire:loading.class` (tambah class tertentu saat loading), `wire:loading.attr` (tambah attribute seperti `disabled`), `wire:loading.remove` (sembunyikan saat loading).

### 2. `wire:target="gotoPage,previousPage,nextPage"`

Menentukan method Livewire mana yang memicu loading ini. Tanpa `wire:target`, loading akan muncul untuk **setiap** aksi Livewire di komponen tersebut.

Di komponen `PropertiList`, method-method untuk pagination adalah:
- `gotoPage($page)` — saat mengklik nomor halaman tertentu
- `previousPage()` — saat mengklik "Previous"
- `nextPage()` — saat mengklik "Next"

Method-method ini disediakan oleh trait `WithPagination` bawaan Livewire.

Loading **tidak akan muncul** untuk aksi lain (misalnya method lain di komponen yang sama).

### 3. Posisi: `items-center justify-center py-4 mb-2`

- `items-center` — center secara vertikal (berguna kalau ada konten lain di flex container)
- `justify-center` — center secara horizontal
- `py-4` — padding atas-bawah 16px (memberi ruang antara header section dan grid)
- `mb-2` — margin bawah 8px (jarak ke grid cards)

Ini membuat spinner berada **di atas grid cards**, center horizontal, bukan fullscreen.

### 4. Spinner SVG

```svg
<svg class="size-8 animate-spin text-orange-600" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
</svg>
```

**Bagian-bagiannya:**

| Elemen | Ukuran | Warna | Fungsi |
|---|---|---|---|
| `svg` | `size-8` (32px) | `text-orange-600` | Container spinner |
| `circle` (track) | r=10, stroke-width=4 | opacity 25% | Lingkaran abu-abu transparan (background track) |
| `path` (indicator) | — | opacity 75% | Jalur oranye yang berputar |

**`animate-spin`**: Class Tailwind yang menerapkan animasi rotasi 360° linear tanpa henti:

```css
@keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
```

Hanya bagian `path` (1/4 lingkaran) yang terlihat berputar, sementara `circle` (lingkaran penuh) tetap diam sebagai track — ini menciptakan efek spinner standar.

**Ukuran spinner:**

| Class | Pixel |
|---|---|
| `size-5` | 20px (ukuran asli sebelum diubah) |
| `size-8` | 32px (ukuran saat ini) |
| `size-10` | 40px |
| `size-20` | 80px |
| `size-24` | 96px |

### 5. Tidak ada teks

Versi sebelumnya memiliki:

```blade
<span class="text-sm text-neutral-500">Memuat...</span>
```

Teks tersebut dihapus karena hanya spinner saja yang diinginkan.

---

## Ringkasan Alur

```
User klik "Next" / nomor halaman
        │
        ▼
Livewire panggil gotoPage() / nextPage() / previousPage()
        │
        ├── 1. Kirim request AJAX ke server
        ├── 2. wire:loading.flex terpicu → spinner muncul (display: flex)
        ├── 3. wire:loading.class pada grid → opacity-40 + pointer-events-none
        │
        ▼
Server selesai proses → return response
        │
        ├── 4. Livewire update DOM (grid cards diganti konten baru)
        ├── 5. wire:loading.flex selesai → spinner hilang (display: none)
        └── 6. wire:loading.class selesai → grid kembali normal
```

---

## Catatan

- Loading screen ini **tidak muncul** saat navigasi dari halaman lain ke `/properti` via `wire:navigate`. Untuk itu perlu pendekatan berbeda (event `livewire:navigating` di layout).
- Loading hanya untuk pagination. Tidak ada loading untuk filter/search karena fitur tersebut belum ada di halaman ini.
- Spinner menggunakan SVG inline, bukan icon font atau gambar eksternal — tidak ada dependency tambahan.
