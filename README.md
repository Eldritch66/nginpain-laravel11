# Nginapin — Arsitektur & Dokumentasi

> Platform sewa kosan dan kontrakan di Bogor. Dibangun dengan Laravel 11 + Livewire 4 + Tailwind CSS 4.

---

## Daftar Isi

1. [Tech Stack & Library](#1-tech-stack--library)
2. [Struktur Folder](#2-struktur-folder)
3. [Route System](#3-route-system)
4. [Database Schema](#4-database-schema)
5. [Authentication & Role](#5-authentication--role)
6. [Alur Booking (End-to-End)](#6-alur-booking-end-to-end)
7. [Livewire Components](#7-livewire-components)
8. [Blade Components](#8-blade-components)
9. [Layout System](#9-layout-system)
10. [Middleware](#10-middleware)
11. [Commands](#11-commands)

---

## 1. Tech Stack & Library

### 1.1 Backend

#### Laravel Framework 11 (`laravel/framework`)

- **Apa itu**: PHP framework untuk backend. Di Next.js, backend adalah API Routes atau Server Actions. Di Laravel, semuanya (routing, database, session, auth) sudah jadi satu paket.
- **Install**: `composer create-project laravel/laravel`
- **Cara kerja**: MVC (Model-View-Controller), tapi project ini pake **Route Closure** (langsung di `routes/web.php`) tanpa Controller terpisah.
- **Versi**: ^11.0

#### Livewire 4 (`livewire/livewire`)

- **Apa itu**: Library PHP + JavaScript untuk bikin komponen interaktif tanpa nulis JS. Mirip React component, tapi state management dan rendering di server (PHP), sementara DOM update dikirim via AJAX.
- **Status**: Ada yang nyebut "full-stack framework for Laravel", ada yang bilang library. Intinya dia duduk di atas Laravel dan extends fungsionalitasnya. Di `composer.json` dia masuk sebagai `require` (bukan `require-dev`), artinya production dependency.
- **Install**: `composer require livewire/livewire` (via Composer karena PHP package)
- **Konsep penting**:
    - Setiap komponen adalah **1 file PHP** (logic) + **1 file Blade** (view)
    - `wire:model` → two-way data binding (mirip `useState` + `onChange` di React)
    - `wire:click` → event handler (mirip `onClick`)
    - `wire:navigate` → SPA-style page navigation tanpa reload (mirip `<Link>` di Next.js)
    - `#[Computed]` → property yang di-cache selama request (mirip `useMemo`)
    - `#[Layout]` → tentukan layout Blade yang dipakai komponen
- **Versi**: ^4.3

#### Laravel Socialite (`laravel/socialite`)

- **Apa itu**: Library untuk OAuth login (Google, GitHub, dll). Mirip NextAuth di Next.js.
- **Install**: `composer require laravel/socialite`
- **Dipakai untuk**: Login Google OAuth
- **Versi**: ^5.28

#### Laravel Tinker (`laravel/tinker`)

- **Apa itu**: REPL (Read-Eval-Print-Loop) untuk Laravel. Mirip `node` di terminal, tapi untuk PHP + Laravel. Bisa akses DB, model, dll langsung dari terminal.
- **Install**: `composer require laravel/tinker` (include by default)
- **Contoh**: `php artisan tinker` → `User::count()` → `Sewa::where('status_sewa', 'pending')->get()`

### 1.2 Frontend

#### Tailwind CSS 4 (`tailwindcss`)

- **Apa itu**: Utility-first CSS framework. Sama persis seperti di Next.js project.
- **Cara setup** (berbeda dengan Tailwind v3):
    1. `npm install tailwindcss @tailwindcss/vite`
    2. Pasang plugin di `vite.config.js`
    3. Import `@import "tailwindcss"` di `resources/css/app.css` (tanpa config file terpisah)
    4. Konfigurasi custom theme lewat `@theme` directive di CSS, bukan `tailwind.config.js`
- **Versi**: ^4.3.1

#### Vite (`vite`)

- **Apa itu**: Build tool untuk frontend assets. Di Next.js pake webpack (Turbopack), di Laravel pake Vite via `laravel-vite-plugin`.
- **Cara kerja**: `npm run dev` → Vite dev server → assets di-reload otomatis. `npm run build` → build assets ke `public/build/`.
- **Plugin**: `laravel-vite-plugin` (integrasi Laravel + Vite), `@tailwindcss/vite` (Tailwind JIT compiler)

### 1.3 Database & Tools

#### Database

<!-- - **Default**: SQLite (`database/database.sqlite`) — untuk development. -->

- **Production**: MySQL (via `.env` override). Konfigurasi di `config/database.php`.
- **Session & Cache & Queue**: Semua pake `database` driver (tabel `sessions`, `cache`, `jobs`).

#### PHP ^8.2

- Minimal PHP versi 8.2. Laravel 11 butuh PHP 8.2+.

#### Laravel Pint (`laravel/pint`)

- **Apa itu**: PHP code formatter (mirip Prettier untuk PHP). Fix otomatis styling kode sesuai PSR-12.
- **Install**: `composer require laravel/pint --dev`
- **Jalankan**: `./vendor/bin/pint`

### 1.4 Package Manager

| Ekosistem  | Manager  | File config     | Perintah                                |
| ---------- | -------- | --------------- | --------------------------------------- |
| PHP        | Composer | `composer.json` | `composer install` / `composer require` |
| JavaScript | npm      | `package.json`  | `npm install` / `npm run dev`           |

**Catatan penting buat developer Next.js**: di Next.js cuma ada `package.json` (npm). Di Laravel ada **dua** package manager:

- **Composer** untuk PHP packages (Laravel, Livewire, Socialite, dll)
- **npm** untuk JavaScript/CSS tools (Tailwind, Vite, dll)

Keduanya harus di-install: `composer install && npm install`.

---

## 2. Struktur Folder

```
nginapin-laravel11/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Auth/
│   │   │       └── SocialiteController.php   # Google OAuth
│   │   └── Middleware/
│   │       └── RoleMiddleware.php            # Proteksi role (penyewa/pemilik)
│   ├── Livewire/                             # ★ Komponen Livewire
│   │   ├── Auth/
│   │   │   ├── Register.php                  # Form registrasi
│   │   │   └── RoleSelect.php                # Pilih role setelah register
│   │   ├── Pembayaran/
│   │   │   └── PembayaranForm.php            # Form pembayaran
│   │   ├── Properti/
│   │   │   ├── PropertiDetail.php            # Detail + booking
│   │   │   └── PropertiList.php              # Daftar properti + pagination
│   │   └── Tiket/
│   │       └── FormBantuan.php               # Form tiket bantuan
│   └── Models/                               # ★ Eloquent Models
│       ├── Admin.php
│       ├── FotoProperti.php
│       ├── Pembayaran.php
│       ├── Properti.php
│       ├── Sewa.php
│       ├── TiketBantuan.php
│       ├── Unit.php
│       └── User.php
├── config/                                   # Konfigurasi Laravel
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   └── services.php                          # Google OAuth credentials
├── database/
│   └── migrations/                           # ★ Schema database
│       ├── 0001_01_01_000000_create_users_table.php
│       ├── 0001_01_01_000001_create_admin_table.php
│       ├── 0001_01_01_000002_create_properti_table.php
│       ├── 0001_01_01_000003_create_foto_properti_table.php
│       ├── 0001_01_01_000004_create_unit_table.php
│       ├── 0001_01_01_000005_create_sewa_table.php
│       ├── 0001_01_01_000006_create_pembayaran_table.php
│       ├── 0001_01_01_000007_create_tiket_bantuan_table.php
│       └── 0001_01_01_000008_create_sessions_table.php
├── docs/
│   └── architecture.md                        # ★ File ini
├── resources/
│   ├── css/
│   │   └── app.css                           # Tailwind CSS entry point
│   ├── js/
│   │   └── app.js                            # JS entry point (cuma import CSS)
│   └── views/                                # ★ Blade templates
│       ├── account/                          # Halaman akun penyewa
│       │   ├── index.blade.php               # Dashboard penyewa
│       │   ├── sewa-index.blade.php          # Daftar sewa
│       │   ├── sewa-detail.blade.php         # Detail sewa
│       │   ├── struk.blade.php               # Bukti pembayaran
│       │   └── pemilik/                      # Halaman akun pemilik
│       │       ├── index.blade.php           # Dashboard pemilik
│       │       ├── properti-index.blade.php   # Daftar properti
│       │       ├── properti-detail.blade.php  # Detail properti + konfirmasi sewa
│       │       ├── properti-tambah.blade.php  # Tambah properti
│       │       └── properti-edit.blade.php    # Edit properti
│       ├── auth/
│       │   └── login.blade.php               # Halaman login
│       ├── components/                       # ★ Blade Components
│       │   ├── footer.blade.php
│       │   ├── header.blade.php
│       │   ├── main-root.blade.php
│       │   └── navigation-profile.blade.php  # Sidebar akun
│       ├── layouts/                          # ★ Layout templates
│       │   ├── account.blade.php             # Layout halaman akun (sidebar)
│       │   ├── app.blade.php                 # Layout public (header + footer)
│       │   └── auth.blade.php                # Layout login/register
│       ├── livewire/                         # ★ View Livewire components
│       │   ├── auth/
│       │   │   ├── register.blade.php
│       │   │   └── role-select.blade.php
│       │   ├── pembayaran/
│       │   │   └── pembayaran-form.blade.php
│       │   ├── properti/
│       │   │   ├── properti-detail.blade.php
│       │   │   └── properti-list.blade.php
│       │   └── tiket/
│       │       └── form-bantuan.blade.php
│       ├── beranda.blade.php                 # Landing page
│       └── tentang.blade.php                 # Halaman tentang kami
└── routes/
    └── web.php                               # ★ Semua routes ada di sini
```

### Perbedaan dengan Next.js (buat developer yang baru pindah)

| Next.js                            | Laravel                                                 |
| ---------------------------------- | ------------------------------------------------------- |
| `app/page.tsx` → route `/`         | `routes/web.php` → `Route::get('/', ...)`               |
| `app/layout.tsx`                   | `resources/views/layouts/app.blade.php`                 |
| `app/loading.tsx`                  | `wire:loading` directive di layout                      |
| `app/properti/[id]/page.tsx`       | Livewire component `PropertiDetail` dipanggil via route |
| Prisma schema → `schema.prisma`    | Migrations → `database/migrations/*.php`                |
| Server Actions (`"use server"`)    | Route closures atau Livewire methods                    |
| `fetch()` / Supabase SDK           | Eloquent ORM (`Properti::find($id)`)                    |
| React Server Components            | Blade templates (server-side by default)                |
| Client Components (`"use client"`) | Livewire components                                     |
| `next-auth`                        | `Socialite` (Google OAuth)                              |
| `tailwind.config.js`               | `@theme` directive in `app.css`                         |

---

## 3. Route System

Semua route didefinisikan di `routes/web.php`. Tidak ada file-based routing seperti Next.js.

### 3.1 Public Routes

| Method | URL                     | Handler                        | View/Component                      |
| ------ | ----------------------- | ------------------------------ | ----------------------------------- |
| GET    | `/`                     | Closure                        | `beranda.blade.php`                 |
| GET    | `/login`                | `Route::view`                  | `auth.login.blade.php`              |
| POST   | `/login`                | Closure (manual auth)          | —                                   |
| GET    | `/register`             | `Register` Livewire            | `livewire.auth.register`            |
| GET    | `/auth/google/redirect` | `SocialiteController@redirect` | —                                   |
| GET    | `/auth/google/callback` | `SocialiteController@callback` | —                                   |
| GET    | `/role`                 | `RoleSelect` Livewire          | `livewire.auth.role-select`         |
| POST   | `/logout`               | Closure                        | —                                   |
| GET    | `/properti`             | `PropertiList` Livewire        | `livewire.properti.properti-list`   |
| GET    | `/properti/{id}`        | `PropertiDetail` Livewire      | `livewire.properti.properti-detail` |
| GET    | `/tentang`              | `Route::view`                  | `tentang.blade.php`                 |

### 3.2 Authenticated Routes (penyewa & pemilik)

Semua route di bawah `Route::middleware('auth')->group(...)`:

| Method   | URL                                     | Deskripsi                                                  |
| -------- | --------------------------------------- | ---------------------------------------------------------- |
| GET      | `/pembayaran/{sewaId}`                  | Form pembayaran (Livewire)                                 |
| GET      | `/account`                              | Dashboard penyewa (redirect pemilik ke `/account/pemilik`) |
| GET      | `/account/sewa`                         | Daftar sewa penyewa                                        |
| GET      | `/account/sewa/{sewaId}`                | Detail sewa                                                |
| GET      | `/account/struk/{sewaId}`               | Bukti pembayaran setelah bayar                             |
| GET      | `/account/pemilik`                      | Dashboard pemilik                                          |
| GET      | `/account/pemilik/properti`             | Daftar properti pemilik                                    |
| GET+POST | `/account/pemilik/properti/tambah`      | Tambah properti                                            |
| GET      | `/account/pemilik/properti/{id}`        | Detail properti + history sewa                             |
| GET+POST | `/account/pemilik/properti/{id}/edit`   | Edit properti                                              |
| POST     | `/account/pemilik/properti/{id}/delete` | Hapus properti                                             |
| POST     | `/sewa/{id}/cancel`                     | Penyewa batalkan sewa (pending → dibatalkan)               |
| POST     | `/sewa/{id}/confirm`                    | Pemilik konfirmasi sewa (pending → aktif)                  |
| POST     | `/sewa/{id}/reject`                     | Pemilik tolak sewa (pending → dibatalkan)                  |
| POST     | `/sewa/{id}/delete`                     | Penyewa hapus sewa yang sudah dibatalkan                   |

### 3.3 Route Closure Pattern

Berbeda dengan Next.js yang file-based, Laravel project ini pake **Route Closure** (fungsi anonim langsung di route). Contoh:

```php
Route::get('/account/sewa/{sewaId}', function ($sewaId) {
    // 1. Ambil user yang login
    $user = Auth::user();

    // 2. Query database pake Eloquent
    $sewa = Sewa::with('properti.foto', 'properti.unit', 'pembayaran')
        ->where('penyewa_id', $user->id)
        ->findOrFail($sewaId);

    // 3. Return view dengan data
    return view('account.sewa-detail', ['sewa' => $sewa]);
})->name('account.sewa.detail');
```

Ini mirip dengan:

```typescript
// Next.js Server Action
export async function getSewaDetail(sewaId: string) {
    const sewa = await prisma.sewa.findUnique({
        where: { id: sewaId, penyewa_id: session.user.id },
        include: { properti: { include: { foto: true } } },
    });
    return sewa;
}
```

Bedanya: di Laravel function langsung di file route, di Next.js biasanya terpisah di Server Action atau API route.

---

## 4. Database Schema

### 4.1 Entity Relationship Diagram (textual)

```
users ──┬── properti (pemilik_id)
        ├── sewa (penyewa_id)
        ├── tiket_bantuan (user_id)
        └── sessions (user_id)

properti ──┬── foto_properti
           ├── unit
           └── sewa

sewa ──── pembayaran
```

### 4.2 Tabel Detail

#### `users`

| Column      | Type                            | Notes                    |
| ----------- | ------------------------------- | ------------------------ |
| id          | bigint AI                       | Primary Key              |
| email       | string unique                   | Login identifier         |
| name        | string                          | Nama user                |
| password    | string nullable                 | Null untuk Google login  |
| role        | enum('new','penyewa','pemilik') | `new` = belum pilih role |
| provider_id | string nullable                 | Google OAuth ID          |
| no_hp       | string nullable                 |                          |
| avatar_url  | text nullable                   |                          |
| timestamps  | created_at, updated_at          |                          |

**Model**: `app/Models/User.php` — extends `Authenticatable` (bukan `Model` biasa, karena Laravel auth butuh class ini).

#### `properti`

| Column              | Type                     | Notes                        |
| ------------------- | ------------------------ | ---------------------------- |
| id                  | bigint AI                |                              |
| pemilik_id          | foreign→users            | Pemilik properti             |
| nama_properti       | string                   |                              |
| tipe                | enum('kost','kontrakan') |                              |
| alamat              | text                     |                              |
| kota                | string                   | Default: "Bogor, Jawa Barat" |
| harga_per_bulan     | int nullable             |                              |
| harga_per_dua_bulan | int nullable             | = harga_per_bulan \* 2       |
| timestamps          |                          |                              |

**Relasi**: `pemilik()` → User, `foto()` → FotoProperti[], `unit()` → Unit, `sewa()` → Sewa[]

#### `unit`

| Column             | Type             | Notes |
| ------------------ | ---------------- | ----- |
| id                 | bigint AI        |       |
| properti_id        | foreign→properti |       |
| luas_bangunan      | decimal nullable |       |
| jumlah_kamar_tidur | smallint         |       |
| jumlah_kamar_mandi | smallint         |       |
| kapasitas_penghuni | smallint         |       |
| lantai             | smallint         |       |
| keterangan         | text nullable    |       |

#### `foto_properti`

| Column      | Type             |
| ----------- | ---------------- |
| id          | bigint AI        |
| properti_id | foreign→properti |
| url         | text             |

#### `sewa`

| Column          | Type                                 | Notes                                          |
| --------------- | ------------------------------------ | ---------------------------------------------- |
| id              | bigint AI                            |                                                |
| penyewa_id      | foreign→users                        |                                                |
| properti_id     | foreign→properti nullable            | nullOnDelete                                   |
| tanggal_mulai   | date                                 |                                                |
| tanggal_selesai | date                                 |                                                |
| durasi_bulan    | smallint                             | >= 2 (CHECK constraint)                        |
| total_harga     | decimal(15,2)                        |                                                |
| status_sewa     | enum('aktif','pending','dibatalkan') | Default dulu `aktif`, sekarang diisi `pending` |
| disetujui_pada  | timestamp nullable                   | Diisi saat pemilik konfirmasi                  |
| timestamps      |                                      |                                                |

**Relasi**: `penyewa()` → User, `properti()` → Properti, `pembayaran()` → Pembayaran[]

**Alur status**:

```
booking → pending → [pemilik konfirmasi] → aktif
                  → [pemilik tolak] → dibatalkan
                  → [penyewa batalkan] → dibatalkan
```

#### `pembayaran`

| Column        | Type                                            | Notes                                      |
| ------------- | ----------------------------------------------- | ------------------------------------------ |
| id            | bigint AI                                       |                                            |
| sewa_id       | foreign→sewa                                    |                                            |
| jumlah        | decimal(15,2)                                   | Grand total (termasuk service fee + pajak) |
| metode        | enum('QRIS','Transfer BCA','PayPal') nullable   |                                            |
| status        | enum('menunggu','lunas','ditolak','kadaluarsa') | Default: 'lunas' (simulasi)                |
| periode_bulan | smallint nullable                               |                                            |
| dibayar_pada  | timestamp nullable                              |                                            |
| timestamps    |                                                 |                                            |

#### `tiket_bantuan`

| Column        | Type                                                    |
| ------------- | ------------------------------------------------------- |
| id            | bigint AI                                               |
| user_id       | foreign→users                                           |
| judul         | string                                                  |
| pesan         | text                                                    |
| kategori      | enum('teknis','pembayaran','properti','akun','lainnya') |
| status        | enum('diproses','selesai','ditutup')                    |
| balasan_admin | text nullable                                           |
| dijawab_oleh  | foreign→admin nullable                                  |
| dijawab_pada  | timestamp nullable                                      |
| timestamps    |                                                         |

#### `admin`

| Column     | Type               |
| ---------- | ------------------ |
| id         | bigint AI          |
| email      | string unique      |
| password   | string nullable    |
| nama       | string             |
| last_login | timestamp nullable |
| timestamps |                    |

---

## 5. Authentication & Role

### 5.1 Cara Login

**Manual (email + password)**:

1. User POST ke `/login` dengan `email` + `password`
2. Route closure mencari user by email
3. Verifikasi password dengan `Hash::check()`
4. `Auth::login($user)` → bikin session
5. Redirect: role `new` → `/role`, lainnya → `/`

**Google OAuth**:

1. User klik "Login Google" → redirect ke `/auth/google/redirect`
2. `SocialiteController@redirect` → redirect ke Google consent screen
3. Google callback ke `/auth/google/callback`
4. `SocialiteController@callback` → cari user by email, atau buat baru
5. Login + redirect sama seperti manual

### 5.2 Role System

User punya field `role` dengan 3 nilai:

| Role      | Arti                                                           |
| --------- | -------------------------------------------------------------- |
| `new`     | Baru register, belum pilih role. Langsung diarahkan ke `/role` |
| `penyewa` | Bisa lihat properti, booking, kelola sewa                      |
| `pemilik` | Bisa tambah/kelola properti, konfirmasi/tolak sewa             |

**RoleMiddleware** (`app/Http/Middleware/RoleMiddleware.php`):

- Cek apakah user login
- Kalau role `new` dan bukan di halaman `/role`, redirect ke `/role`
- Kalau role tidak sesuai, redirect ke `/`

**Catatan**: Middleware ini sudah didaftarkan di Laravel tapi tidak dipakai di route manapun. Role checking dilakukan manual di setiap route closure dengan:

```php
if ($user->role !== 'pemilik') {
    return redirect('/account')->with('error', 'Anda bukan pemilik.');
}
```

### 5.3 Logout

- POST `/logout` → `Auth::logout()`, invalidate session, regenerate CSRF token
- Redirect ke `/`

### 5.4 CSRF Protection

Laravel otomatis nge-protect semua POST/PUT/DELETE request dari CSRF. Setiap form harus menyertakan:

```blade
<form method="POST" action="/sewa/{{ $sewa->id }}/cancel">
    @csrf
    ...
</form>
```

Tanpa `@csrf`, Laravel akan return 419 error.

---

## 6. Alur Booking (End-to-End)

Ini alur lengkap dari user mencari properti sampai sewa dikonfirmasi.

### Step 1: Lihat Daftar Properti

```
URL: /properti
Component: PropertiList (Livewire)
Layout: layouts.app
```

- Menampilkan semua properti dalam grid (6 per page, pagination)
- Properti dengan status sewa `aktif` atau `pending` ditandai "Terisi"
- Klik "Lihat Detail" → `/properti/{id}`

### Step 2: Detail Properti + Booking

```
URL: /properti/{id}
Component: PropertiDetail (Livewire)
Layout: layouts.app
```

- Menampilkan foto, spesifikasi unit, harga
- User pilih durasi sewa (min 2 bulan, increment/decrement)
- **Pricing formula**:
    - `total_harga` = `harga_per_dua_bulan` + (extra_bulan × `harga_per_bulan`)
    - `service_fee` = 25.000 (fixed)
    - `pajak` = 10% dari `total_harga` (dibulatkan ke atas)
    - `grand_total` = `total_harga` + `service_fee` + `pajak`
- Klik "Sewa Sekarang" → method `book()`:
    1. Cek login → kalau belum, redirect ke `/login`
    2. Cek role → kalau `new`, redirect ke `/role`
    3. Cek bukan pemilik → kalau pemilik, error flash
    4. Cek bukan properti sendiri
    5. Cek ketersediaan
    6. **Buat Sewa** dengan `status_sewa = 'pending'`
    7. Redirect ke `/pembayaran/{sewaId}`

### Step 3: Pembayaran

```
URL: /pembayaran/{sewaId}
Component: PembayaranForm (Livewire)
Layout: layouts.app
```

- Menampilkan summary sewa (nama properti, tanggal, rincian biaya)
- Pilih metode: QRIS / Transfer BCA / PayPal
- Klik "Konfirmasi Pembayaran" → method `submit()`:
    1. Validasi metode pembayaran
    2. **Buat record Pembayaran** dengan `status = 'lunas'` (simulasi — tanpa payment gateway beneran)
    3. **Tidak mengubah status sewa** — tetap `pending`
    4. Redirect ke `/account/struk/{sewaId}`

### Step 4: Struk / Bukti Pembayaran

```
URL: /account/struk/{sewaId}
View: account.struk
Layout: layouts.account
```

- Menampilkan receipt / invoice:
    - Nomor invoice (#INV/00001)
    - Nama properti + foto
    - Nama penyewa & pemilik
    - Periode sewa
    - Rincian harga: 2 bulan pertama, extra bulan, biaya layanan, pajak, total
    - Metode pembayaran
    - Status: **"Menunggu Konfirmasi Pemilik"** (kotak kuning peringatan)
- Tombol "Lihat Sewa Saya" → ke daftar sewa

### Step 5a: Pemilik Konfirmasi (Terima)

```
URL: /account/pemilik/properti/{id}
View: account.pemilik.properti-detail
```

- Pemilik buka detail properti
- Lihat data penyewa (nama, email, tanggal, durasi, total harga)
- Klik **"Terima"** → POST `/sewa/{id}/confirm`
    - `status_sewa` → `'aktif'`
    - `disetujui_pada` → `now()`
- Sewa aktif! Penyewa bisa lihat status berubah jadi "Aktif"

### Step 5b: Pemilik Tolak

```
URL: /account/pemilik/properti/{id}
```

- Klik **"Tolak"** → POST `/sewa/{id}/reject`
    - `status_sewa` → `'dibatalkan'`
- Penyewa lihat status "Dibatalkan" + refund info

### Step 5c: Penyewa Batalkan (sebelum dikonfirmasi)

```
URL: /account/sewa/{id}
```

- Klik **"Batalkan Sewa"** → POST `/sewa/{id}/cancel`
    - `status_sewa` → `'dibatalkan'`

### Flow Diagram

```
[User] → /properti → /properti/{id} → [BOOK] → /pembayaran/{id} → [BAYAR] → /account/struk/{id}
                                                                                  │
                                                                                  ▼
                                                                         Menunggu Konfirmasi
                                                                                  │
                                                                         ┌─────────┴──────────┐
                                                                         ▼                    ▼
                                                            [Pemilik Terima]         [Pemilik Tolak]
                                                                 │                       │
                                                                 ▼                       ▼
                                                            status=aktif           status=dibatalkan
                                                                                       │
                                                                                 [Refund info]
```

---

## 7. Livewire Components

Livewire adalah inti dari interaktivitas frontend di project ini. Setiap komponen terdiri dari:

1. **File PHP** (`app/Livewire/...`): logika, state, method, computed properties
2. **File Blade** (`resources/views/livewire/...`): template HTML

### 7.1 PropertiList (`/properti`)

| File  | Path                                                        |
| ----- | ----------------------------------------------------------- |
| PHP   | `app/Livewire/Properti/PropertiList.php`                    |
| Blade | `resources/views/livewire/properti/properti-list.blade.php` |

**Fungsi**: Menampilkan daftar properti dengan pagination.

**State**:

- `$perPage` (int, default 6)

**Computed Properties**:

- `properti()`: Query semua properti, cek mana yang `isOccupied`, return paginated

**Layout**: `layouts.app`

### 7.2 PropertiDetail (`/properti/{id}`)

| File  | Path                                                          |
| ----- | ------------------------------------------------------------- |
| PHP   | `app/Livewire/Properti/PropertiDetail.php`                    |
| Blade | `resources/views/livewire/properti/properti-detail.blade.php` |

**Fungsi**: Detail properti + booking form.

**State**:

- `$properti` (Properti model, di-mount dari ID)
- `$months` (int, default 2)

**Computed Properties**:

- `isTersedia()`: Cek tidak ada sewa aktif/pending
- `isPemilik()`: Cek apakah user adalah pemilik properti
- `totalHarga()`, `serviceFee()`, `tax()`, `grandTotal()`: Kalkulasi harga
- `startDate()`, `endDate()`: Tanggal sewa

**Key Method**:

- `book()`: Validasi + buat Sewa + redirect ke `/pembayaran/{id}`

### 7.3 PembayaranForm (`/pembayaran/{sewaId}`)

| File  | Path                                                            |
| ----- | --------------------------------------------------------------- |
| PHP   | `app/Livewire/Pembayaran/PembayaranForm.php`                    |
| Blade | `resources/views/livewire/pembayaran/pembayaran-form.blade.php` |

**Fungsi**: Form pembayaran dengan pilihan metode.

**State**:

- `$sewa` (Sewa model, di-mount dari ID)
- `$metode` (string, pilihan metode)

**Key Method**:

- `submit()`: Validasi → buat Pembayaran (lunas) → redirect ke struk

### 7.4 Register (`/register`)

| File  | Path                                               |
| ----- | -------------------------------------------------- |
| PHP   | `app/Livewire/Auth/Register.php`                   |
| Blade | `resources/views/livewire/auth/register.blade.php` |

### 7.5 RoleSelect (`/role`)

| File  | Path                                                  |
| ----- | ----------------------------------------------------- |
| PHP   | `app/Livewire/Auth/RoleSelect.php`                    |
| Blade | `resources/views/livewire/auth/role-select.blade.php` |

### 7.6 FormBantuan (di halaman beranda)

| File  | Path                                                    |
| ----- | ------------------------------------------------------- |
| PHP   | `app/Livewire/Tiket/FormBantuan.php`                    |
| Blade | `resources/views/livewire/tiket/form-bantuan.blade.php` |

### 7.7 Penting: `wire:navigate`

Semua link internal pake `wire:navigate` (bukan `href` biasa). Ini membuat navigasi berjalan secara SPA (tanpa reload halaman penuh).

Di Blade:

```blade
<a wire:navigate href="/properti">Lihat Properti</a>
```

Di Livewire method:

```php
return $this->redirect('/account/sewa', navigate: true);
```

**Analogi Next.js**: `wire:navigate` = `<Link>` dari `next/link`.

---

## 8. Blade Components

Blade Components adalah potongan template yang bisa dipakai ulang. Dipanggil dengan `x-nama-component`.

### 8.1 Header (`x-header`)

**File**: `resources/views/components/header.blade.php`

- Navigasi utama (logo, link Properti, About)
- Desktop: menu horizontal
- Mobile: hamburger menu dengan AlpineJS (`x-data`, `x-show`)
- Conditional: kalau login → "Dashboard", kalau belum → "Login"

### 8.2 Footer (`x-footer`)

**File**: `resources/views/components/footer.blade.php`

Single-line footer: logo + copyright di kiri, navigasi di kanan. Tanpa HR pemisah (single cohesive section).

### 8.3 NavigationProfile (`x-navigation-profile`)

**File**: `resources/views/components/navigation-profile.blade.php`

Sidebar navigasi di halaman akun:

- Home
- Sewa Properti
- Kelola Properti (hanya untuk pemilik; penyewa lihat disabled + modal warning)

### 8.4 MainRoot (`x-main-root`)

**File**: `resources/views/components/main-root.blade.php`

Wrapper untuk halaman public. Memberi max-width + centering.

---

## 9. Layout System

Layout di Laravel mirip dengan `layout.tsx` di Next.js App Router. Bedanya:

- Next.js: layout otomatis berdasarkan folder
- Laravel: layout dipilih manual pake `@extends()` atau `#[Layout]` attribute

### 9.1 `layouts.app`

**File**: `resources/views/layouts/app.blade.php`

Layout utama untuk halaman public. Berisi:

- `<head>` + Vite assets
- `<x-header />` (sticky top)
- `<main>` → `@yield('content')` + `$slot` (tempat konten halaman)
- `<x-footer />`
- `@livewireScripts` (required untuk Livewire)

**Dipakai oleh**: `beranda.blade.php`, `tentang.blade.php`, dan Livewire components dengan `#[Layout('layouts.app')]`.

### 9.2 `layouts.account`

**File**: `resources/views/layouts/account.blade.php`

Layout untuk halaman akun (sesudah login). Berisi:

- `<x-header />`
- Success/error flash messages (`session('success')`, `session('error')`)
- Grid sidebar (350px) + konten
- Sidebar: `<x-navigation-profile />`
- Konten: `@yield('content')` + `$slot`
- **Tidak ada footer**

**Dipakai oleh**: Semua halaman di `/account/*`.

### 9.3 `layouts.auth`

**File**: `resources/views/layouts/auth.blade.php`

Layout minimal untuk halaman login/register. Hanya berisi `{{ $slot }}` tanpa header/footer.

**Dipakai oleh**: `Register` dan `RoleSelect` Livewire components via `#[Layout('layouts.auth')]`.

### 9.4 Cara Kerja `@extends` vs `#[Layout]`

Ada dua cara pilih layout:

**Cara 1: `@extends` di Blade** (untuk regular views)

```blade
{{-- resources/views/beranda.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>Halo</h1>
@endsection
```

**Cara 2: `#[Layout]` attribute** (untuk Livewire components)

```php
// app/Livewire/Properti/PropertiDetail.php
#[Layout('layouts.app')]
class PropertiDetail extends Component
{
    // ...
}
```

Keduanya sama-sama nge-render view di dalam layout yang ditentukan.

---

## 10. Middleware

### 10.1 RoleMiddleware

**File**: `app/Http/Middleware/RoleMiddleware.php`

Fungsi:

1. Cek user login
2. Kalau role `new` dan bukan di `/role`, redirect ke `/role`
3. Kalau role cocok dengan salah satu yang diizinkan, lanjut
4. Kalau tidak cocok, redirect ke `/` dengan error

**Tidak dipakai di route manapun** saat ini. Proteksi role dilakukan manual di setiap route closure.

### 10.2 Built-in Middleware

- `auth` — cek user sudah login (dipakai di grup route `/account/*`, `/sewa/*`)
- `guest` — cek user belum login (tidak dipakai)
- `throttle` — rate limiting (tidak dipakai)
- `verified` — cek email terverifikasi (tidak dipakai, fitur verifikasi email belum diimplementasikan)

---

## 11. Commands

| Action                  | Command                                   | Keterangan                                       |
| ----------------------- | ----------------------------------------- | ------------------------------------------------ |
| Dev server (PHP)        | `php artisan serve`                       | Jalankan Laravel dev server (port 8000)          |
| Dev server (Vite)       | `npm run dev`                             | Jalankan Vite untuk hot reload CSS/JS            |
| Build assets            | `npm run build`                           | Build Tailwind + JS ke `public/build/`           |
| Run all tests           | `phpunit` or `./vendor/bin/phpunit`       |                                                  |
| Run single test         | `phpunit --filter test_name`              |                                                  |
| Format PHP code         | `./vendor/bin/pint`                       | Otomatis perbaiki styling kode                   |
| Run migration           | `php artisan migrate`                     | Jalankan migration yang pending                  |
| Fresh migrate + seed    | `php artisan migrate:fresh --seed`        | Hapus semua tabel, migrate ulang, isi data dummy |
| Tinker (REPL)           | `php artisan tinker`                      | Interactive PHP shell untuk testing              |
| Cache clear             | `php artisan optimize:clear`              | Clear semua cache Laravel                        |
| Make migration          | `php artisan make:migration nama_tabel`   | Buat file migration baru                         |
| Make Livewire component | `php artisan make:livewire NamaComponent` | Buat file PHP + Blade untuk Livewire             |

### 11.1 Cara Menjalankan Project

```bash
# 1. Install PHP dependencies
composer install

# 2. Install frontend dependencies
npm install

# 3. Copy .env (kalau belum ada)
copy .env.example .env    # Windows
cp .env.example .env      # Linux/Mac

# 4. Generate app key
php artisan key:generate

# 5. Run migrations
php artisan migrate

# 6. Start dev servers (butuh 2 terminal)
# Terminal 1:
php artisan serve
# Terminal 2:
npm run dev

# Buka http://localhost:8000
```

---

## Istilah Penting (untuk developer Next.js)

| Istilah Laravel           | Analogi Next.js                 | Penjelasan                                |
| ------------------------- | ------------------------------- | ----------------------------------------- |
| Blade                     | JSX / TSX                       | Templating engine                         |
| `@extends('layouts.app')` | layout.tsx + children           | Template inheritance                      |
| `@yield('content')`       | `{children}`                    | Tempat konten dinamis                     |
| `{{ $variable }}`         | `{variable}`                    | Escape output                             |
| `{!! $variable !!}`       | `<div dangerouslySetInnerHTML>` | Raw output (tanpa escape)                 |
| `@auth` / `@guest`        | `if (session)`                  | Cek login di Blade                        |
| `@csrf`                   | —                               | Token keamanan form                       |
| `Route::get(...)`         | `app/page.tsx`                  | Definisi route                            |
| `$this->redirect(...)`    | `router.push()`                 | Redirect di Livewire                      |
| `view('nama.view')`       | —                               | Render Blade view                         |
| `Sewa::find($id)`         | `prisma.sewa.findUnique()`      | Query database (Eloquent)                 |
| `$sewa->properti->nama`   | `sewa.properti.nama`            | Relasi Eloquent (lazy loading)            |
| `$sewa->load('properti')` | `include: { properti: true }`   | Eager loading                             |
| `->with('properti.foto')` | —                               | Nested eager loading                      |
| `collect([...])`          | Array methods                   | Laravel Collection class                  |
| `Carbon::parse(...)`      | `dayjs()` / `date-fns`          | Date manipulation                         |
| `number_format(...)`      | `Intl.NumberFormat`             | Format angka                              |
| `session()->flash(...)`   | —                               | Flash message (sekali tampil lalu hilang) |
| `php artisan`             | —                               | CLI tool untuk berbagai perintah          |
| Composer                  | npm                             | PHP package manager                       |
| Packagist                 | npm registry                    | PHP package registry                      |

---

## Filosofi Arsitektur

1. **No Controllers**: Project ini sengaja tidak pake Controllers. Semua logic di Route Closures (untuk halaman statis) dan Livewire Components (untuk halaman interaktif). Ini lebih sederhana untuk project skala kecil/menengah.

2. **Livewire > JavaScript**: Semua interaktivitas (booking, pembayaran, form) pake Livewire, bukan React/Vue/Alpine. Hanya hamburger menu di header yang pake AlpineJS.

3. **Simulated Payment**: Belum ada integrasi payment gateway beneran. Pembayaran langsung dianggap `lunas` setelah user klik konfirmasi.

4. **Role-based Access**: Dua role utama (penyewa + pemilik) dengan halaman terpisah. Penyewa booking, pemilik konfirmasi.

5. **SPA via Livewire**: `wire:navigate` memberikan pengalaman SPA tanpa perlu React/Next.js. Tapi loading bar dari Livewire tidak bisa dikustomisasi dengan mudah (spinner tidak bisa dipasang di layout level).
