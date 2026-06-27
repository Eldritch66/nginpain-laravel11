# Kenapa Layout Kacau Kalau Tidak `npm run dev/build`?

## TL;DR

Tailwind CSS cuma **menyertakan class yang benar-benar dipakai** di file Blade kamu. Class baru di footer/layout tidak akan muncul sampai kamu menjalankan ulang `npm run dev` atau `npm run build`.

---

## Cara Kerja Tailwind CSS + Vite

### 1. Scanning (JIT Compiler)

Saat kamu menjalankan:

```bash
npm run dev      # → vite (development, hot reload)
npm run build    # → vite build (production, one-shot)
```

Vite + `@tailwindcss/vite` akan:

1. **Memindai semua file** di `resources/views/`, `app/Livewire/`, dan folder lain yang terkonfigurasi
2. **Mendeteksi class Tailwind** yang dipakai (misal: `bg-white`, `text-sm`, `hover:text-orange-600`, `lg:max-w-[1750px]`)
3. **Menghasilkan CSS** yang **hanya berisi class-class tersebut** — tidak seluruh Tailwind (ribuan class)
4. Menyimpan hasilnya ke `public/build/assets/app-{hash}.css`

### 2. Kenapa Footer Kacau?

Misalnya kamu punya file `resources/views/components/footer.blade.php`:

```blade
<footer class="mx-auto w-full lg:max-w-[1750px] border-t border-l border-r border-neutral-200 bg-white ...">
```

Class `lg:max-w-[1750px]`, `border-neutral-200`, `rounded-none sm:rounded-t-lg`, `hover:text-orange-600`, dll. adalah class **kustom** yang **baru ditambahkan**.

| Situasi | Akibat |
|---|---|
| `npm run build` **sebelum** footer diedit | CSS cuma berisi class lama. Class baru **tidak dikenal** browser → style tidak teraplikasi |
| `npm run build` **sesudah** footer diedit | CSS berisi class baru → footer tampil normal |

**Tanpa rebuild**, browser tidak tahu apa itu `lg:max-w-[1750px]` atau `hover:text-orange-600` karena tidak ada aturan CSS untuk class tersebut.

### 3. `npm run dev` vs `npm run build`

| Perintah | Kapan pakai | Efek |
|---|---|---|
| `npm run dev` | **Saat ngoding** frontend | Vite watch file, CSS diupdate otomatis setiap kali file berubah (hot reload). Butuh berjalan di terminal terpisah. |
| `npm run build` | **Satu kali** setelah selesai ngoding | Hasilkan file CSS/JS final di `public/build/`. Tidak perlu `npm run dev` lagi sampai ada perubahan CSS/JS berikutnya. |

Buat development sehari-hari yang **hanya bermain di backend (PHP/Blade)** tanpa menyentuh CSS/JS:

```bash
# Cukup jalankan sekali:
npm run build

# Lalu cukup:
php artisan serve
```

### 4. Cara Cek Apakah CSS Sudah Up-to-Date

Buka browser → Inspect Element → lihat `<head>`. Cari tag CSS yang di-inject Vite:

- **Kalau `npm run dev` jalan**: Akan ada `<script type="module" src="http://localhost:5173/...">` — Vite dev server
- **Kalau cuma `npm run build`**: Akan ada `<link rel="stylesheet" href="/build/assets/app-{hash}.css">` — file statis

Kalau file CSS di `public/build/assets/` sudah ada, tapi layout tetap kacau, **pasti** ada class baru yang belum di-build.

### 5. Perbaikan Cepat

```bash
# Build ulang asset
npm run build

# Atau kalau masih develop frontend
npm run dev
# (biarkan berjalan, buka terminal kedua untuk php artisan serve)
```

### 6. Catatan: `resources/js/app.js`

File ini direferensi oleh `@vite('resources/js/app.js')` di layout. Meskipun file JS tidak ada (kosong), Vite tetap memprosesnya. Selama ada `npm run build` atau `npm run dev`, semuanya berjalan normal. File ini penting sebagai **entry point** bagi Vite untuk memproses `resources/css/app.css`.

---

## Kesimpulan

- Tailwind JIT compiler = hanya class yang **terdeteksi dipakai** yang masuk ke CSS final
- Setiap kali menambah/mengubah class Tailwind di Blade → **harus rebuild** (`npm run dev` atau `npm run build`)
- `npm run build` sekali cukup kalau kamu cuma kerja backend. `npm run dev` diperlukan kalau kamu sering ganti-ganti frontend
