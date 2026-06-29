# Sistem Pengkodean Otomatis

> Dokumentasi format kode unik untuk setiap entitas di Nginapin. Kode dibuat otomatis melalui Eloquent `creating` hook dan bersifat `unique` (bukan primary key).

---

## Daftar Isi

1. [Properti (`kode_properti`)](#1-properti-kode_properti)
2. [Sewa (`kode_booking`)](#2-sewa-kode_booking)
3. [Pembayaran (`kode_bayar`)](#3-pembayaran-kode_bayar)
4. [Tiket Bantuan (`no_tiket`)](#4-tiket-bantuan-no_tiket)
5. [Pola Umum](#5-pola-umum)

---

## 1. Properti (`kode_properti`)

| Item | Detail |
|------|--------|
| **Model** | `App\Models\Properti` |
| **Kolom** | `kode_properti` (string 10, unique, nullable) |
| **Format** | `{PREFIX}{NNN}` |
| **Contoh** | `KSN001`, `KNK015` |
| **Tujuan** | Identifikasi aset fisik tanpa harus lihat primary key `id` |

### Format

```
{PREFIX}{3 digit urutan}
```

| Bagian | Panjang | Keterangan |
|--------|---------|------------|
| Prefix | 3 | `KSN` (kost) / `KNK` (kontrakan) |
| Urutan | 3 digit | `001` – `999`, reset per tipe properti |

### Cara Kerja

```php
// app/Models/Properti.php — booted()
static::creating(function ($properti) {
    $prefix = $properti->tipe === 'kost' ? 'KSN' : 'KNK';
    $last = static::where('tipe', $properti->tipe)
        ->orderBy('kode_properti', 'desc')
        ->lockForUpdate()
        ->value('kode_properti');
    $next = $last ? (int) substr($last, -3) + 1 : 1;
    $properti->kode_properti = $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
});
```

---

## 2. Sewa (`kode_booking`)

| Item | Detail |
|------|--------|
| **Model** | `App\Models\Sewa` |
| **Kolom** | `kode_booking` (string 15, unique, nullable) |
| **Format** | `SW-{YYMM}-{NNN}` |
| **Contoh** | `SW-2606-001`, `SW-2607-015` |
| **Tujuan** | Nomor pesanan (booking/order number) untuk kuitansi / invoice |

### Format

```
SW-{YY}{MM}-{3 digit urutan}
```

| Bagian | Panjang | Keterangan |
|--------|---------|------------|
| Prefix | 2 | `SW` (Sewa) |
| YY | 2 | 2 digit tahun terakhir (contoh: `26` untuk 2026) |
| MM | 2 | 2 digit bulan (contoh: `06` untuk Juni) |
| Urutan | 3 digit | `001` – `999`, **reset setiap bulan** |

### Cara Kerja

```php
// app/Models/Sewa.php — booted()
static::creating(function ($sewa) {
    $prefix = 'SW';
    $ym = now()->format('ym');
    $last = static::where('kode_booking', 'like', $prefix.'-'.$ym.'-%')
        ->orderBy('kode_booking', 'desc')
        ->lockForUpdate()
        ->value('kode_booking');
    $next = $last ? (int) substr($last, -3) + 1 : 1;
    $sewa->kode_booking = $prefix.'-'.$ym.'-'.str_pad($next, 3, '0', STR_PAD_LEFT);
});
```

### Contoh Produksi

| Bulan | Kode |
|-------|------|
| Juni 2026 (record pertama) | `SW-2606-001` |
| Juni 2026 (record ke-2) | `SW-2606-002` |
| Juli 2026 (record pertama) | `SW-2607-001` |

---

## 3. Pembayaran (`kode_bayar`)

| Item | Detail |
|------|--------|
| **Model** | `App\Models\Pembayaran` |
| **Kolom** | `kode_bayar` (string 15, unique, nullable) |
| **Format** | `PAY-{METODE}-{NNN}` |
| **Contoh** | `PAY-QRS-001`, `PAY-BCA-005`, `PAY-PYP-003` |
| **Tujuan** | Rekonsiliasi pembayaran dengan payment gateway / mutasi bank manual |

### Format

```
PAY-{kode metode}-{3 digit urutan}
```

| Bagian | Panjang | Keterangan |
|--------|---------|------------|
| Prefix | 3 | `PAY` (Payment) |
| Kode Metode | 3 | Kode metode pembayaran |
| Urutan | 3 digit | `001` – `999`, **reset per metode** |

### Mapping Metode

| Metode | Kode |
|--------|------|
| QRIS | `QRS` |
| Transfer BCA | `BCA` |
| PayPal | `PYP` |

### Cara Kerja

```php
// app/Models/Pembayaran.php — booted()
static::creating(function ($pembayaran) {
    $map = [
        'QRIS' => 'QRS',
        'Transfer BCA' => 'BCA',
        'PayPal' => 'PYP',
    ];
    $prefix = 'PAY';
    $method = $map[$pembayaran->metode] ?? 'XXX';
    $last = static::where('kode_bayar', 'like', $prefix.'-'.$method.'-%')
        ->orderBy('kode_bayar', 'desc')
        ->lockForUpdate()
        ->value('kode_bayar');
    $next = $last ? (int) substr($last, -3) + 1 : 1;
    $pembayaran->kode_bayar = $prefix.'-'.$method.'-'.str_pad($next, 3, '0', STR_PAD_LEFT);
});
```

---

## 4. Tiket Bantuan (`no_tiket`)

| Item | Detail |
|------|--------|
| **Model** | `App\Models\TiketBantuan` |
| **Kolom** | `no_tiket` (string 15, unique, nullable) |
| **Format** | `TKT-{KATEGORI}-{NNN}` |
| **Contoh** | `TKT-TEK-001`, `TKT-PAY-005`, `TKT-PRP-003` |
| **Tujuan** | Standar customer service — nomor tiket untuk melacak progres keluhan |

### Format

```
TKT-{kode kategori}-{3 digit urutan}
```

| Bagian | Panjang | Keterangan |
|--------|---------|------------|
| Prefix | 3 | `TKT` (Tiket) |
| Kode Kategori | 3 | Kode kategori tiket |
| Urutan | 3 digit | `001` – `999`, **reset per kategori** |

### Mapping Kategori

| Kategori | Kode |
|----------|------|
| teknis | `TEK` |
| pembayaran | `PAY` |
| properti | `PRP` |
| akun | `AKN` |
| lainnya | `LNY` |

### Cara Kerja

```php
// app/Models/TiketBantuan.php — booted()
static::creating(function ($tiket) {
    $map = [
        'teknis' => 'TEK',
        'pembayaran' => 'PAY',
        'properti' => 'PRP',
        'akun' => 'AKN',
        'lainnya' => 'LNY',
    ];
    $prefix = 'TKT';
    $kategori = $map[$tiket->kategori] ?? 'XXX';
    $last = static::where('no_tiket', 'like', $prefix.'-'.$kategori.'-%')
        ->orderBy('no_tiket', 'desc')
        ->lockForUpdate()
        ->value('no_tiket');
    $next = $last ? (int) substr($last, -3) + 1 : 1;
    $tiket->no_tiket = $prefix.'-'.$kategori.'-'.str_pad($next, 3, '0', STR_PAD_LEFT);
});
```

---

## 5. Pola Umum

Semua pengkodean mengikuti pola yang sama:

### 5.1 Arsitektur

```
Eloquent Model
  └── booted() ──→ static::creating()
                      ├── Skip jika sudah diisi manual
                      ├── Cari kode terakhir dengan LIKE prefix + lockForUpdate()
                      ├── Increment nomor urut
                      └── Set kolom kode

Migration
  └── add_column (string, unique, nullable, after id)
       └── Backfill data existing dengan counter terpisah
```

### 5.2 Aturan

| Aturan | Penjelasan |
|--------|------------|
| Bukan primary key | `id` (auto-increment) tetap sebagai PK, kode hanya unique identifier |
| Auto-generated | Dibuat otomatis via `creating` hook, tidak perlu diisi manual |
| Race condition safe | `lockForUpdate()` mencegah duplikasi kode saat concurrent request |
| Skip jika diisi | Jika kolom sudah terisi, hook tidak menimpa |
| Backfill migration | Data existing mendapat kode saat migration dijalankan |

### 5.3 Ringkasan Format

| Entitas | Kolom | Format | Sequence Reset |
|---------|-------|--------|----------------|
| Properti | `kode_properti` | `KSN001` / `KNK001` | Per tipe |
| Sewa | `kode_booking` | `SW-2606-001` | Per bulan |
| Pembayaran | `kode_bayar` | `PAY-QRS-001` | Per metode |
| Tiket Bantuan | `no_tiket` | `TKT-TEK-001` | Per kategori |

### 5.4 Implementasi di View

Kode ditampilkan di halaman-halaman berikut:

| Kode | Halaman |
|------|---------|
| `kode_booking` | `account/sewa-index`, `account/sewa-detail`, `account/struk`, `account/pemilik/properti-detail` |
| `no_tiket` | `account/tiket-index` |
| `kode_properti` | `account/sewa-index`, `account/sewa-detail`, `account/struk`, `account/pemilik/properti-*` |
| `kode_bayar` | Filament admin (belum ada view publik) |

### 5.5 Konfigurasi

Semua pengkodean ada di file model masing-masing (`app/Models/`). Migration ada di `database/migrations/` dengan prefix `add_*`.
