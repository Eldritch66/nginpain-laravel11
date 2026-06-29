# Panduan Deploy Laravel ke Railway (Lengkap)

> Panduan ini mencakup seluruh proses deploy Laravel 11 ke Railway dari awal hingga aplikasi berjalan lancar, termasuk fix atas error yang umum terjadi.

---

## Daftar Isi

1. [Prasyarat](#1-prasyarat)
2. [Push Project ke GitHub](#2-push-project-ke-github)
3. [Generate APP_KEY](#3-generate-app_key)
4. [Buat Project & Service di Railway](#4-buat-project--service-di-railway)
5. [Set Environment Variables](#5-set-environment-variables)
6. [Fix Trusted Proxies (HTTPS/SSL)](#6-fix-trusted-proxies-httpsssl)
7. [Deploy & Migration](#7-deploy--migration)
8. [Seed & Storage Link](#8-seed--storage-link)
9. [Generate URL Publik](#9-generate-url-publik)
10. [Troubleshooting](#10-troubleshooting)

---

## 1. Prasyarat

- Akun [GitHub](https://github.com)
- Akun [Railway](https://railway.app) (login pakai GitHub)
- Git sudah terinstall
- PHP 8.3+ & Composer sudah terinstall (untuk generate `APP_KEY`)

---

## 2. Push Project ke GitHub

```bash
git init
git add .
git commit -m "Initial commit"

# Buat repo kosong di github.com, lalu:
git remote add origin https://github.com/username/nama-repo.git
git push -u origin main
```

> Ganti `username/nama-repo` dengan repo Anda.

---

## 3. Generate APP_KEY

```bash
php artisan key:generate --show
```

Simpan output-nya (contoh: `base64:A1b2C3d...`). Akan dipakai di step 5.

---

## 4. Buat Project & Service di Railway

### a. Buat Project

1. Buka [railway.app](https://railway.app) → login
2. Klik **New Project**
3. Pilih **Deploy from GitHub repo**
4. Pilih repo Anda
5. Railway mulai build — akan gagal karena belum ada database (abaikan dulu)

### b. Tambah Service MySQL

1. Klik **+ New** (di halaman project)
2. Pilih **Database** → **MySQL**
3. Tunggu beberapa detik sampai status **Running** (centang hijau)

Railway akan generate variable MySQL seperti:

| Variable | Contoh Nilai |
|----------|-------------|
| `MYSQLHOST` | `mysql.railway.internal` |
| `MYSQLPORT` | `3306` |
| `MYSQLDATABASE` | `railway` |
| `MYSQLUSER` | `root` |
| `MYSQLPASSWORD` | `xxxxxxxx` |

### c. Catatan Penting: Internal vs Public Host

Railway menyediakan **dua hostname** untuk MySQL:

- **Internal** (`mysql.railway.internal`) — lebih cepat, hanya bisa diakses antar service dalam satu project Railway
- **Public** (`roundhouse.proxy.rlwy.net`) — bisa diakses dari luar, lebih lambat

Gunakan **internal host** (`mysql.railway.internal`) untuk komunikasi antara service Laravel dan MySQL di Railway.

---

## 5. Set Environment Variables

### a. Cara Mengisi Variable

Klik service Laravel (bukan MySQL) → tab **Variables**. Klik **New Variable**, isi Key dan Value.

### b. Variable yang Wajib

| Key | Value | Keterangan |
|-----|-------|------------|
| `APP_KEY` | `base64:A1b2C3d...` | Dari hasil generate step 3 |
| `APP_ENV` | `production` | Mode production |
| `APP_DEBUG` | `false` | Matikan debug |
| `APP_URL` | `https://namaproject.up.railway.app` | Isi setelah dapat URL (step 9) |
| `DB_CONNECTION` | `mysql` | Pakai MySQL |
| `DB_HOST` | `mysql.railway.internal` | Host internal MySQL |
| `DB_PORT` | `3306` | Port MySQL |
| `DB_DATABASE` | `railway` | Nama database |
| `DB_USERNAME` | `root` | User MySQL |
| `DB_PASSWORD` | `xxxxxxxx` | Password MySQL (copy dari service MySQL) |
| `SESSION_DRIVER` | `database` | Session pakai database |
| `QUEUE_CONNECTION` | `database` | Queue pakai database |
| `CACHE_STORE` | `file` | Cache pakai file |
| `RAILPACK_SKIP_MIGRATIONS` | `true` | Skip auto-migration (bisa dihapus setelah deploy pertama sukses) |

> **PENTING:** Isi `DB_HOST`, `DB_PASSWORD`, dll dengan **nilai langsung** (copy dari service MySQL), jangan pakai `${{MySQL.MYSQLHOST}}`. Variable referencing `${{}}` kadang tidak di-resolve Railpack saat build phase, menyebabkan Laravel fallback ke SQLite.

> **PENTING:** `DB_PASSWORD` bisa dicek di tab Variables service MySQL. Nilainya tidak sama dengan password local.

### c. Variable Bawaan dari `.env`

Railway auto-import variable dari file `.env` di repo. Beberapa sudah benar secara default:
`SESSION_DRIVER`, `QUEUE_CONNECTION`, `CACHE_STORE` — biasanya sudah sesuai, tidak perlu diubah.

**Namun**, perhatikan variable berikut yang perlu di-update:
- `APP_ENV` → ubah dari `local` ke `production`
- `APP_DEBUG` → ubah dari `true` ke `false`

---

## 6. Fix Trusted Proxies (HTTPS/SSL)

### Masalah

Railway menerima request HTTPS dari browser, tapi meneruskannya ke container Laravel via **HTTP biasa** (port 8080). Akibatnya Laravel menganggap semua request sebagai HTTP, dan semua URL asset (CSS, JS, Livewire, redirect) digenerate dengan `http://`.

Browser akan **memblokir asset** tersebut karena halaman diakses via HTTPS (Mixed Content error).

### Gejala

- CSS/JS tidak muncul (Vite, Livewire)
- Form Livewire tidak bisa submit
- Error di Console browser: *"Mixed Content: ... was loaded over HTTPS, but requested an insecure script"*

### Solusi

Edit `bootstrap/app.php`, tambahkan `trustProxies`:

```php
use Illuminate\Http\Request;

->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(
        at: '*',
        headers: Request::HEADER_X_FORWARDED_FOR |
                 Request::HEADER_X_FORWARDED_HOST |
                 Request::HEADER_X_FORWARDED_PORT |
                 Request::HEADER_X_FORWARDED_PROTO |
                 Request::HEADER_X_FORWARDED_AWS_ELB
    );

    $middleware->alias([
        'role' => RoleMiddleware::class,
    ]);
})
```

**Cara kerja:** Laravel membaca header `X-Forwarded-Proto: https` dari Railway proxy dan menganggap request asli sebagai HTTPS. Semua URL yang digenerate Laravel akan menggunakan `https://`.

### File Lengkap (`bootstrap/app.php`)

```php
<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO | Request::HEADER_X_FORWARDED_AWS_ELB);

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

---

## 7. Deploy & Migration

### a. Deploy

Setelah variable terisi dan perubahan kode di-commit, Railway auto-deploy. Tunggu sampai status **Deployed**.

### b. Masalah: Build Gagal di Railpack

Jika build gagal, cek:

1. **PHP version** — Pastikan `composer.json` pakai `"php": "^8.3"` (Railpack default PHP 8.3+). Beberapa package (seperti `openspout/openspout` via Filament) butuh PHP 8.3.
2. **PHP extensions** — Tambahkan ke `composer.json` jika dibutuhkan:
   ```json
   "require": {
       "php": "^8.3",
       "ext-intl": "*",
       "ext-zip": "*"
   }
   ```
3. **Tidak perlu `railpack.json`** — Railpack auto-detect semuanya dari `composer.json`.

### c. Masalah: Migration Gagal "table already exists"

**Gejala:** Error `SQLSTATE[42S01]: Base table or view already exists: 1050 Table 'sessions' already exists`

**Penyebab:** `SESSION_DRIVER=database` menyebabkan Laravel auto-create tabel `sessions` saat boot, sebelum migration sempat membuatnya.

**Solusi di Console Railway:**

```bash
# Hapus tabel sessions yang bentrok
php artisan tinker --execute="DB::statement('DROP TABLE IF EXISTS sessions')"

# Jalankan migration
php artisan migrate
```

Atau kalau banyak tabel bermasalah:

```bash
# Hapus semua tabel
php artisan db:wipe

# Jalankan ulang migration
php artisan migrate
```

> **Catatan:** `migrate:fresh` tidak fix karena Laravel tetap auto-create `sessions` sebelum migration jalan. Gunakan `db:wipe` atau drop manual.

### d. Jalankan Migration Manual

Buka tab **Console** di service Laravel (ikon `>_`), jalankan:

```bash
php artisan migrate
```

Output sukses:
```
INFO  Preparing database.
Creating migration table ................... 22ms DONE

INFO  Running migrations.
0001_01_01_000000_create_users_table ....... 72ms DONE
0001_01_01_000001_create_admin_table ....... 26ms DONE
...
```

### e. Hapus `RAILPACK_SKIP_MIGRATIONS`

Setelah migration manual berhasil, hapus variable `RAILPACK_SKIP_MIGRATIONS` dari Railway Variables agar deploy selanjutnya auto-migrate.

---

## 8. Seed & Storage Link

Setelah migration sukses (opsional):

```bash
php artisan db:seed
php artisan storage:link
```

Seed akan membuat:
- User pemilik: `pemilik@test.com` / `password`
- User penyewa: `penyewa@test.com` / `password`
- 3 properti + 1 sewa aktif

---

## 9. Generate URL Publik

1. Buka service Laravel → tab **Settings**
2. Di bagian **Networking** → klik **Generate Domain**
3. Railway akan memberi URL seperti: `https://namaproject.up.railway.app`

### Update `APP_URL`

Kembali ke tab **Variables**, cari `APP_URL` → ubah value ke URL tersebut (tanpa trailing slash):

```
https://namaproject.up.railway.app
```

Di Console Railway, clear cache:

```bash
php artisan optimize:clear
```

---

## 10. Troubleshooting

### a. Error 500 / Halaman Putih

Cek penyebab:

```bash
cat storage/logs/laravel.log
```

Solusi umum:

```bash
php artisan optimize:clear
php artisan config:clear
php artisan view:clear
```

### b. "No application encryption key has been specified"

`APP_KEY` belum diisi. Generate dan tambahkan ke Railway Variables:

```bash
php artisan key:generate --show
```

### c. Mixed Content (CSS/JS/Livewire tidak muncul)

**Gejala:** Halaman muncul tanpa style, form tidak bisa submit, error di Console browser.

**Penyebab:** HTTPS di-terminate Railway, Laravel kira request HTTP.

**Solusi:** Tambahkan `trustProxies` di `bootstrap/app.php` (lihat step 6).

### d. Migration gagal "table already exists"

**Solusi:**

```bash
php artisan tinker --execute="DB::statement('DROP TABLE IF EXISTS sessions')"
php artisan migrate
```

### e. Database "Access denied"

`DB_HOST` atau `DB_PASSWORD` salah. Cek variable di service MySQL:

1. Klik service MySQL → tab **Variables**
2. Copy nilai `MYSQLHOST`, `MYSQLPASSWORD`, dll
3. Paste ke variable Laravel (nilai langsung, bukan `${{}}`)

### f. Upload Foto Hilang Setelah Restart

Railway pakai file system **ephemeral** (sementara). Solusi:
- **Jangka pendek:** Upload ulang
- **Jangka panjang:** Simpan ke cloud storage (S3, Cloudinary, dll)

### g. Build Gagal karena PHP Extensions

Tambahkan ke `composer.json`:

```json
"require": {
    "php": "^8.3",
    "ext-intl": "*",
    "ext-zip": "*"
}
```

### h. Build Gagal karena PHP Version

Railpack default pakai PHP 8.3. Pastikan `composer.json`:

```json
"require": {
    "php": "^8.3"
}
```

---

## Lampiran: Arsitektur Railway

```
Browser ──HTTPS──▶ Railway Proxy ──HTTP──▶ Nginx:8080 ──PHP──▶ Laravel
                                                    │
                                                    └── internal ──▶ MySQL:3306
```

- Railway proxy terminate SSL, kirim HTTP biasa ke container
- Set header `X-Forwarded-Proto: https` agar Laravel tahu request asli HTTPS
- `trustProxies` di Laravel baca header tersebut

---

## Lampiran: Daftar Perintah Console Railway

| Perintah | Fungsi |
|----------|--------|
| `php artisan migrate` | Jalankan migration |
| `php artisan migrate:fresh` | Hapus semua tabel + migrate ulang |
| `php artisan db:wipe` | Hapus semua tabel |
| `php artisan db:seed` | Isi data dummy |
| `php artisan storage:link` | Aktifkan upload foto |
| `php artisan optimize:clear` | Bersihkan semua cache |
| `php artisan config:cache` | Cache konfigurasi |
| `php artisan key:generate --show` | Generate APP_KEY |
| `php artisan tinker` | REPL untuk debug |
| `php artisan tinker --execute="..."` | Jalankan perintah langsung |
| `cat storage/logs/laravel.log` | Lihat error log |
| `ls -la public/build/` | Cek hasil build Vite |
| `cat public/build/manifest.json` | Cek mapping asset Vite |

---

Dokumentasi ini disusun berdasarkan pengalaman deploy aktual dan mencakup semua error yang berhasil diatasi.
