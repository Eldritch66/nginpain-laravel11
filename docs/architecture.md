# Nginapin — Architecture

## Stack

Backend: **PHP 8.3 / Laravel 11 / Livewire 4 / Midtrans SDK 2.6**
Frontend: **Tailwind CSS 4 / Vite 5**
Database: **SQLite (dev) / MySQL (production)** — driver session/cache/queue: database
Testing: **PHPUnit 10**

Detail lengkap di [`docs/tech-stack.md`](tech-stack.md).

---

## Folder Structure

| Folder | Isi |
|--------|-----|
| `app/Livewire/` | Komponen Livewire (Auth, Pembayaran, Properti, Tiket) |
| `app/Models/` | Eloquent models (User, Properti, Sewa, Pembayaran, dll) |
| `app/Services/` | Service class (MidtransService) |
| `app/Http/Controllers/` | Cuma SocialiteController + MidtransCallbackController |
| `routes/web.php` | **Semua** route — closure-based, tanpa controller kecuali di atas |
| `resources/views/` | Blade templates (layouts, components, livewire views, pages) |
| `database/migrations/` | Schema database (8 migration files) |
| `config/` | Konfigurasi Laravel (app, auth, database, midtrans, services) |

---

## Key Routes

Semua di `routes/web.php`, prefix `/` (web), bukan `/api`.

### Public

| Method | URL | Fungsi |
|--------|-----|--------|
| GET | `/` | Landing page |
| GET/POST | `/login` | Login manual (email+password) |
| GET | `/register` | Register (Livewire) |
| GET | `/auth/google/*` | Google OAuth (Socialite) |
| GET | `/properti` | Daftar properti + pagination (Livewire) |
| GET | `/properti/{id}` | Detail properti + booking (Livewire) |

### Authenticated

| Method | URL | Fungsi |
|--------|-----|--------|
| GET | `/pembayaran` | Form bayar via Midtrans (Livewire) |
| GET | `/payment/finish` | Redirect dari Snap → cek status → create Sewa |
| POST | `/payment/check-status` | Poll Midtrans API update status |
| GET | `/account/struk/{sewaId}` | Receipt setelah bayar |
| GET | `/account/sewa*` | Daftar/detail sewa penyewa |
| GET/POST | `/account/pemilik/*` | Dashboard + CRUD properti pemilik |
| POST | `/sewa/{id}/confirm\|reject\|cancel` | Aksi sewa (pemilik/penyewa) |

---

## Database

### Entity Relations

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

### Key Tables

**users**: email, password (nullable untuk Google), role (`new`/`penyewa`/`pemilik`), provider_id (Google OAuth)

**properti**: pemilik_id, nama_properti, tipe (`kost`/`kontrakan`), alamat, harga_per_bulan, harga_per_dua_bulan

**sewa**: penyewa_id, properti_id, tanggal_mulai/berakhir, durasi_bulan, total_harga, biaya_layanan, status_sewa (`pending`/`aktif`/`dibatalkan`), disetujui_pada, kode_booking (`SW-{YYMM}-{NNN}`)

**pembayaran**: sewa_id, kode_bayar (`PAY-{METHOD}-{NNN}`), jumlah, metode (dari Midtrans: `bank_transfer`, `credit_card`, `gopay`, dll), status (`menunggu`/`lunas`/`ditolak`/`kadaluarsa`), snap_token, midtrans_transaction_id, dibayar_pada

### Status Lifecycle

**Sewa**: `pending` (baru booking) → pemilik confirm → `aktif` / pemilik tolak atau penyewa cancel → `dibatalkan`

**Pembayaran**: `menunggu` → Midtrans settlement → `lunas` / deny atau expire → `ditolak`/`kadaluarsa`

---

## Auth & Role

- **Login**: manual (email+password via `Hash::check`) atau Google OAuth (Socialite)
- **3 roles**: `new` (baru register, belum pilih), `penyewa`, `pemilik`
- Setelah register → redirect ke `/role` untuk pilih role
- Proteksi role manual di setiap route closure (`if ($user->role !== 'pemilik')`). RoleMiddleware sudah ada tapi tidak dipakai.

---

## Alur Booking → Pembayaran

```
PropertiDetail::book()
  → simpan data booking ke SESSION (0 baris di DB)
  → redirect ke /pembayaran

PembayaranForm::submit()
  → baca dari session
  → generate order_id (PAY-{userId}-{random})
  → Snap::getSnapToken($params)
  → simpan booking ke CACHE (key: booking_{orderId}, TTL 2 jam)
  → redirect ke Midtrans Snap

/payment/finish?order_id=...
  → baca dari CACHE → fallback SESSION → fallback Midtrans API
  → kalau capture/settlement: create Sewa + Pembayaran di DB → redirect ke struk
  → kalau pending: create Sewa + Pembayaran → redirect ke struk (+ Cek Status)
  → kalau deny/expire: hapus cache, error, jangan create apa-apa
  → kalau data hilang tapi Midtrans confirm sukses: error "Hubungi admin"
```

Properti tetap tersedia sampai Midtrans mengonfirmasi pembayaran (tidak ada Sewa di DB sebelumnya).

---

## Pola Arsitektur

| Pola | Detail |
|------|--------|
| **No controllers** | Route closures + Livewire components. Controller cuma untuk Socialite + Midtrans callback. |
| **Livewire > JS** | Semua interaktivitas via `wire:click`, `wire:loading`, `wire:navigate`. AlpineJS cuma dipakai di hamburger menu. |
| **No API routes** | 100% web routes. Tidak ada REST/JSON API. |
| **Booking via Cache** | Data booking disimpan di Cache (file driver) + Session — bukan di DB sampai payment sukses. |
| **Midtrans via SDK** | `midtrans/midtrans-php` v2.6.2 via `Snap::getSnapToken`. Jangan set `Config::$curlOptions` (error 10023 di PHP 8.3). |
| **Polling, not webhook** | Untuk localhost: user klik "Cek Status" → `Transaction::status()`. Webhook (`POST /midtrans/callback`) cuma jalan di production. |
