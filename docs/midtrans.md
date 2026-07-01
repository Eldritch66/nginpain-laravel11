# Integrasi Midtrans — Nginapin

## Ringkasan

**Midtrans Snap** sebagai payment gateway (Sandbox). Sewa + Pembayaran dicreate **hanya setelah Midtrans mengonfirmasi pembayaran sukses**.

## Alur Pembayaran

```
User klik "Sewa Sekarang" di halaman properti
        ↓
PropertiDetail::book() — simpan ke SESSION (0 baris di DB)
        ↓
Redirect ke /pembayaran (tanpa parameter)
        ↓
PembayaranForm — klik "Bayar via Midtrans"
        ↓
Generate order_id (PAY-{userId}-{random})
        ↓
Snap::getSnapToken($params) — call Midtrans API
        ↓
Simpan booking ke CACHE (key: booking_{orderId}, TTL 2 jam)
        ↓
Redirect ke halaman Snap Midtrans
        ↓
User pilih metode & bayar di Snap
        ↓
Browser redirect ke /payment/finish?order_id=PAY-xxx
        ↓
Baca dari CACHE dulu → fallback SESSION → fallback Midtrans API
        ↓
Kalau data booking LENGKAP + Midtrans sukses:
    - create SEWA (aktif/pending) + PEMBAYARAN (lunas/menunggu) di DB
    - redirect ke /account/struk/{sewaId}
        ↓
Kalau data booking HILANG tapi Midtrans sukses:
    - redirect ke /account/sewa dengan error "Hubungi admin"
        ↓
Kalau data booking HILANG dan Midtrans pending:
    - redirect ke /pembayaran dengan error "Sewa ulang"
        ↓
Kalau Midtrans deny/expire:
    - hapus cache + redirect ke /pembayaran dengan error
```

### Properti tetap tersedia sampai bayar sukses

Booking (session + cache) tidak memengaruhi `isTersedia`. Sewa baru dicreate di `/payment/finish` setelah Midtrans mengonfirmasi.

## Struk Page

- Halaman tujuan **setelah** pembayaran selesai
- Menampilkan status pembayaran (lunas/menunggu/ditolak/kadaluarsa)
- Tombol **"Cek Status Pembayaran"** — polling Midtrans API untuk update status
- Hanya bisa diakses kalau Sewa sudah tercreate di DB

## File yang Dibuat/Dimodifikasi

| File | Status | Keterangan |
|------|--------|------------|
| `.env` | Diubah | `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, `MIDTRANS_IS_PRODUCTION` |
| `config/midtrans.php` | **Baru** | Config dari `.env` |
| `database/migrations/2026_07_01_000001_add_midtrans_to_pembayaran_table.php` | **Baru** | Kolom `snap_token`, `midtrans_transaction_id`; `metode` varchar; default `status` = menunggu |
| `app/Services/MidtransService.php` | **Baru** | Wrapper Midtrans SDK (`getSnapToken`, `status`, `updateStatus`, `mapStatus`) |
| `app/Http/Controllers/MidtransCallbackController.php` | **Baru** | Handle webhook dari Midtrans (baca dari `$request->all()`) |
| `app/Livewire/Properti/PropertiDetail.php` | Diubah | `book()` simpan ke session, redirect ke `/pembayaran` |
| `app/Livewire/Pembayaran/PembayaranForm.php` | Diubah | Baca session, generate order_id, call Snap, simpan ke Cache |
| `app/Models/Pembayaran.php` | Diubah | `snap_token`, `midtrans_transaction_id` fillable; mapping kode bayar |
| `resources/views/account/struk.blade.php` | Diubah | Status payment + tombol "Cek Status" |
| `resources/views/livewire/pembayaran/pembayaran-form.blade.php` | Diubah | Hanya satu tombol "Bayar via Midtrans" |
| `routes/web.php` | Diubah | Route `/pembayaran`, `/payment/finish`, `/payment/check-status` |

## Konfigurasi

`.env`:
```env
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_IS_PRODUCTION=false
```

Service inisialisasi SDK:
```php
Config::$serverKey = config('midtrans.server_key');
Config::$clientKey = config('midtrans.client_key');
Config::$isProduction = config('midtrans.is_production');
```

> **Jangan** set `Config::$curlOptions` — menyebabkan error "Undefined array key 10023" di PHP 8.3 + Midtrans SDK 2.6.2.

## Merchant ID

```
M002153788
```

## Testing di Sandbox

### Kartu Kredit (3DS)

| Field | Isi |
|-------|-----|
| Card Number | `4111 1111 1111 1111` |
| Expiry | `12/28` |
| CVV | `123` |
| **OTP/3DS** | **`112233`** (wajib) |

### BCA VA

1. Pilih **BCA Virtual Account** di Snap
2. Catat nomor VA
3. Buka [simulator.sandbox.midtrans.com/bca/va/index](https://simulator.sandbox.midtrans.com/bca/va/index)
4. Masukkan nomor VA → **Inquire** → **Bayar**
5. Kembali ke struk → **"Cek Status Pembayaran"**

### QRIS / GoPay / BNI VA

| Metode | Cara |
|--------|------|
| **QRIS** | Scan QR / [simulator.sandbox.midtrans.com/v2/qris/index](https://simulator.sandbox.midtrans.com/v2/qris/index) |
| **GoPay** | Pilih GoPay → QR muncul → copy URL ke simulator |
| **BNI VA** | Catat nomor VA → bayar di simulator |

## Status Mapping

| Midtrans | Database |
|----------|----------|
| `capture` / `settlement` | `lunas` |
| `pending` | `menunggu` |
| `deny` / `cancel` | `ditolak` |
| `expire` | `kadaluarsa` |

## Webhook

Endpoint: `POST /midtrans/callback` (tanpa CSRF).
Untuk localhost, Midtrans tidak bisa kirim notifikasi — gunakan **"Cek Status Pembayaran"** di halaman struk.

## Catatan Penting

- Midtrans SDK v2.6.2 (`midtrans/midtrans-php`), kompatibel PHP 8.3
- Booking data disimpan di **Cache** (file driver, TTL 2 jam) + **Session** — redundant
- Data booking tidak memengaruhi ketersediaan properti
- Tidak perlu Ngrok untuk testing (browser redirect + polling cukup)
- Route `/payment/finish` fallback: Cache → Session → Midtrans API cek
