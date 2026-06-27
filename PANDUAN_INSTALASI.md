# Panduan Instalasi — Nginapin

Panduan untuk menjalankan project Nginapin di komputer sendiri.

---

## Prasyarat

| Software | Versi Min | Cek Instalasi     |
| -------- | --------- | ----------------- |
| PHP      | 8.2       | `php -v`          |
| Composer | 2.x       | `composer -V`     |
| Node.js  | 18+       | `node -v`         |
| npm      | 9+        | `npm -v`          |
| MySQL    | 5.7+      | `mysql --version` |
| Git      | -         | `git --version`   |

**Alternatif (all-in-one):**

- [Laragon](https://laragon.org/) (Windows) — sudah include PHP, Composer, MySQL, Node.js

---

## Langkah Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/Eldritch66/nginpain-laravel11.git
cd nginpain-laravel11
```

### 2. Install Semua Dependencies

```bash
composer install
npm install
```

### 3. Build Frontend Assets

```bash
npm run build
```

### 4. Buat File `.env`

```bash
copy .env.example .env    # Windows
```

### 5. Generate App Key

```bash
php artisan key:generate
```

### 6. Setup Database

Ada dua pilihan:

#### Opsi A: MySQL (yang dipakai di production)

1. Buka phpMyAdmin (atau terminal MySQL) dan buat database baru:

    ```sql
    CREATE DATABASE nginapin_kontrakan_kosan;
    ```

2. Edit file `.env`, sesuaikan dengan MySQL kamu:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nginapin_kontrakan_kosan
    DB_USERNAME=root
    DB_PASSWORD=
    ```

### 7. Jalankan Migration + Seeder

```bash
php artisan migrate --seed
```

Perintah ini akan:

- Membuat semua tabel (users, properti, sewa, pembayaran, tiket_bantuan, admin, dll)
- Mengisi data contoh (properti, sewa, admin)

### 8. Storage Link

```bash
php artisan storage:link
```

### 9. Jalankan Server

```bash
php artisan serve
```

Buka **http://localhost:8000** di browser.

---

## Akun yang Tersedia (Seeder)

| Role    | Email                               | Password   |
| ------- | ----------------------------------- | ---------- |
| Admin   | `admin@nginapin.com`                | `password` |
| Pemilik | Lihat di tabel `users` hasil seeder | `password` |
| Penyewa | Lihat di tabel `users` hasil seeder | `password` |

**Admin panel:** http://localhost:8000/admin

---

## Catatan Penting

### Google Login

Fitur login dengan Google **tidak akan berfungsi** di komputer lain karena membutuhkan Client ID dan Client Secret yang terdaftar di Google Cloud Console. Untuk mengaktifkannya:

1. Buat project di https://console.cloud.google.com/
2. Aktifkan Google OAuth API
3. Buat credential OAuth, atur Redirect URI ke `http://localhost:8000/auth/google/callback`
4. Di `.env`, tambahkan:
    ```env
    GOOGLE_CLIENT_ID=xxx.apps.googleusercontent.com
    GOOGLE_CLIENT_SECRET=xxx
    GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
    ```
5. Tambahkan juga di `config/services.php`:
    ```php
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],
    ```

### Foto Properti

Gambar properti default mungkin tidak muncul karena path-nya absolute. Untuk menambahkan foto:

1. Letakkan gambar di `storage/app/public/properti/`
2. Buka phpMyAdmin, di tabel `foto_properti`, ubah kolom `url` ke path gambar yang sesuai

### Ada Error?

- **419 Page Expired** → buka `.env`, pastikan `SESSION_DRIVER=database` dan sudah `php artisan migrate`
- **404 Not Found** → pastikan `php artisan serve` jalan dan Vite sudah `npm run build` (atau `npm run dev`)
- **SQLSTATE[HY000]** → cek koneksi database di `.env`, pastikan MySQL sedang jalan
- **Class not found** → jalankan `composer dump-autoload`

---

## Reset Database (kalau mau mulai dari awal)

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan menghapus semua data, membuat ulang tabel, dan mengisi data contoh.

---

## Perintah Cepat

```bash
composer install                    # Install / update PHP dependencies
npm install                         # Install frontend dependencies
npm run build                       # Build CSS + JS untuk production
npm run dev                         # Hot reload mode (butuh terminal terpisah)
php artisan serve                   # Jalankan web server (port 8000)
php artisan migrate                 # Jalankan migration yang pending
php artisan migrate:fresh --seed    # Reset database + isi data contoh
php artisan key:generate            # Generate APP_KEY
php artisan storage:link            # Link storage ke public
php artisan tinker                  # Interactive PHP shell
./vendor/bin/pint                   # Format PHP code
./vendor/bin/phpunit                # Jalankan semua test
```
