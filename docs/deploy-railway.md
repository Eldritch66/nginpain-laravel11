# Panduan Deploy ke Railway

> Platform: Railway (railway.app) — otomatis detect Laravel, gratis tier sampai batas tertentu.

---

## Prasyarat

- Akun [GitHub](https://github.com)
- Akun [Railway](https://railway.app) (bisa login pakai GitHub)
- Git sudah terinstall di komputer lokal
- PHP + Composer sudah terinstall di komputer lokal (untuk generate `APP_KEY`)

---

## 1. Push Project ke GitHub

Buka terminal di folder project, lalu:

```bash
# Init git (kalau belum)
git init

# Tambah semua file
git add .

# Commit pertama
git commit -m "Initial commit"

# Buat repo baru di github.com (jangan centang README/.gitignore)
# Lalu jalankan:
git remote add origin https://github.com/username/nginapin.git
git push -u origin main
```

> Ganti `username/nginapin` dengan repo Anda.

---

## 2. Generate APP_KEY (lokal)

`APP_KEY` harus beda untuk setiap environment. Generate dulu dari komputer lokal:

```bash
php artisan key:generate --show
```

Output contoh: `base64:A1b2C3d4E5f6G7h8I9j0K1l2M3n4O5p6Q7r8S9t0U1v2W3x4Y5z6`

**Simpan hasilnya** — akan dipakai di step 5.

---

## 3. Buat Project di Railway

1. Buka [railway.app](https://railway.app) → login
2. Klik **New Project**
3. Pilih **Deploy from GitHub repo**
4. Pilih repo `username/nginapin`
5. Railway mulai build — akan gagal karena belum ada database

> Abaikan dulu error-nya. Lanjut ke step 4.

---

## 4. Tambah Service MySQL

Di dashboard Railway:

1. Klik **+ New**
2. Pilih **Database** → **MySQL**
3. Tunggu beberapa detik sampai MySQL siap (ada centang hijau)

Railway otomatis buat environment variables di service MySQL:
| Variable | Contoh Value |
|----------|-------------|
| `MYSQLHOST` | `roundhouse.proxy.rlwy.net` |
| `MYSQLPORT` | `3306` |
| `MYSQLDATABASE` | `railway` |
| `MYSQLUSER` | `root` |
| `MYSQLPASSWORD` | `xxxxxxxx` |

---

## 5. Set Environment Variables di Service Laravel

Klik service Laravel → tab **Variables**. Tambah satu per satu:

| Variable | Value |
|----------|-------|
| `APP_KEY` | `base64:A1b2C3d...` (dari step 2) |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://nginapin.up.railway.app` (ganti nanti setelah dapat URL) |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |
| `SESSION_DRIVER` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `CACHE_STORE` | `file` |

### Cara Input Variable

```
Key:   DB_HOST
Value: ${{MySQL.MYSQLHOST}}
```

> **`${{ServiceName.ENV_VAR}}`** = fitur Railway untuk referensi otomatis antar service.
> Jadi `${{MySQL.MYSQLHOST}}` otomatis mengambil host dari service MySQL.

Setelah semua variable masuk, Railway otomatis build ulang.

---

## 6. Tunggu Build & Deploy Selesai

Proses build di Railway meliputi:
1. Install dependencies PHP (`composer install --no-dev`)
2. Install & build frontend (`npm ci && npm run build`)
3. Optimasi Laravel (`php artisan optimize`)
4. Start Nginx + PHP-FPM

Tunggu sampai status **Deployed** (centang hijau).

---

## 7. Jalankan Migration

Buka tab **Railway Console** di service Laravel (ada ikon terminal), lalu jalankan:

```bash
php artisan migrate
```

Output:
```
Migration table created successfully.
Migrating: 0001_01_01_000000_create_users_table
Migrated:  0001_01_01_000000_create_users_table
...
```

> Kalau error "Access denied for user", cek variable `DB_HOST`, `DB_PORT`, dll — pastikan format `${{MySQL.xxx}}` benar atau langsung copy value dari service MySQL.

---

## 8. Seed Database (Opsional — untuk testing)

Kalau mau isi data dummy:

```bash
php artisan db:seed
```

Ini akan membuat:
- User pemilik: `pemilik@test.com` / password: `password`
- User penyewa: `penyewa@test.com` / password: `password`
- 3 properti + 1 sewa aktif

---

## 9. Storage Link (Upload Foto)

Karena Railway pakai file system sementara, storage link perlu dibuat setiap deploy. Jalankan di Console:

```bash
php artisan storage:link
```

Output: `The [public/storage] link has been connected.`

> **Catatan**: File upload akan hilang setiap Railway melakukan restart. Untuk production sungguhan, upload sebaiknya disimpan ke cloud storage (S3, Cloudinary, dll). Tapi untuk MVP/demo, ini cukup.

---

## 10. Dapatkan URL

Railway otomatis kasih domain:
- Buka service Laravel → tab **Settings**
- Lihat bagian **Domains** → ada URL seperti `https://nginapin.up.railway.app`

Domain bisa diganti ke custom domain di halaman yang sama.

Setelah itu, update variable `APP_URL` di Railway dengan URL tersebut, lalu deploy ulang.

---

## Troubleshooting

### Error 500 / Halaman Putih

Buka Railway Console, jalankan:

```bash
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
```

### Error "No application encryption key has been specified"

`APP_KEY` belum diisi di Railway Variables. Generate dengan:

```bash
php artisan key:generate --show
```

Lalu tambahkan sebagai variable di Railway.

### Error Database "Access denied"

Cek semua variable yang pakai `${{MySQL.xxx}}` sudah benar. Bisa coba langsung copy value dari service MySQL:

1. Klik service MySQL → tab **Variables**
2. Copy nilai `MYSQLHOST`, `MYSQLPORT`, dll
3. Paste manual ke variable Laravel (tanpa `${{}}`)

### Upload Foto Hilang Setelah Restart

Ini karena Railway pakai file system **ephemeral** (sementara). Solusi jangka pendek: upload ulang. Solusi permanen: ganti disk `public` ke S3 (AWS) atau Cloud Storage lain.

---

## Ringkasan Perintah di Console Railway

| Perintah | Fungsi |
|----------|--------|
| `php artisan migrate` | Jalankan migration database |
| `php artisan db:seed` | Isi data dummy |
| `php artisan storage:link` | Aktifkan upload foto |
| `php artisan optimize:clear` | Bersihkan semua cache |
| `php artisan key:generate --show` | Generate APP_KEY |
| `php artisan tinker` | REPL untuk debug |

---

## Arsitektur Railway

```
┌──────────────────────────────────────────┐
│              RAILWAY APP                 │
│                                          │
│  ┌─────────────────┐  ┌──────────────┐  │
│  │  Laravel Service │  │  MySQL DB    │  │
│  │                  │  │              │  │
│  │  - Nginx         │  │  - Host      │  │
│  │  - PHP 8.2       │  │  - Port      │  │
│  │  - Laravel       │  │  - Database  │  │
│  │  - Vite Build    │  │  - User      │  │
│  └────────┬─────────┘  └──────┬───────┘  │
│           │                   │          │
│           └─── ${{MySQL.*}} ──┘          │
└──────────────────────────────────────────┘
```
