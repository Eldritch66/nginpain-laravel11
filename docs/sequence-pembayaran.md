# Sequence Diagram — Pembayaran Penyewa

> Alur lengkap dari penyewa melakukan booking hingga pembayaran, berdasarkan implementasi kode yang ada.

---

## 1. Booking Properti (Pra-Pembayaran)

```
Penyewa          PropertiDetail         Sewa           Properti          Unit
   |                    |                 |                 |              |
   |── Buka /properti ─→|                 |                 |              |
   |   /{id}            |                 |                 |              |
   |                    |── load ─────────|                 |              |
   |                    |   properti ────────────────────────────────────→|
   |                    |   + unit ─────────────────────────────────────→|
   |                    |   + foto        |                 |              |
   |                    |← data ─────────|                 |              |
   |                    |                 |                 |              |
   |← Tampilkan detail -|                 |                 |              |
   |   & kalkulator     |                 |                 |              |
   |   harga            |                 |                 |              |
   |                    |                 |                 |              |
   |── Atur durasi ────→|                 |                 |              |
   |   (min 2 bulan)    |── hitung harga -|                 |              |
   |                    |   total_harga   |                 |              |
   |                    |   + biaya_layanan|                 |              |
   |                    |   + pajak       |                 |              |
   |                    |                 |                 |              |
   |── Klik "Sewa      |                 |                 |              |
   |    Sekarang"      |                 |                 |              |
   |                    |                 |                 |              |
   |                    |── validasi ────→|                 |              |
   |                    |   1. sudah      |                 |              |
   |                    |      login?     |                 |              |
   |                    |   2. role bukan |                 |              |
   |                    |      'new'?     |                 |              |
   |                    |   3. role bukan |                 |              |
   |                    |      'pemilik'? |                 |              |
   |                    |   4. bukan      |                 |              |
   |                    |      properti   |                 |              |
   |                    |      sendiri?   |                 |              |
   |                    |   5. tersedia?  |── cek sewa ────→|              |
   |                    |                 |   aktif/pending |              |
   |                    |                 |← tersedia ─────|              |
   |                    |                 |                 |              |
   |                    |── create() ────→|                 |              |
   |                    |   penyewa_id    |                 |              |
   |                    |   properti_id   |                 |              |
   |                    |   tgl_mulai     |                 |              |
   |                    |   tgl_selesai   |                 |              |
   |                    |   durasi_bulan  |                 |              |
   |                    |   total_harga   |                 |              |
   |                    |   biaya_layanan |                 |              |
   |                    |   status='pending'|               |              |
   |                    |                 |── INSERT ──────→|              |
   |                    |← sewa.id ──────|                 |              |
   |                    |                 |                 |              |
   |← redirect ────────|                 |                 |              |
   |  /pembayaran/{id}  |                 |                 |              |
```

---

## 2. Pembayaran

```
Penyewa          PembayaranForm         Sewa           Pembayaran      Properti
   |                    |                 |                 |              |
   |── Buka             |                 |                 |              |
   |   /pembayaran/     |                 |                 |              |
   |   {sewaId}         |                 |                 |              |
   |                    |                 |                 |              |
   |                    |── load sewa ───→|                 |              |
   |                    |   (penyewa_id)  |                 |              |
   |                    |← data ─────────|                 |              |
   |                    |   + properti    |                 |              |
   |                    |                 |                 |              |
   |← Tampilkan form —-|                 |                 |              |
   |   summary sewa    |                 |                 |              |
   |   + pilihan       |                 |                 |              |
   |   metode bayar    |                 |                 |              |
   |                    |                 |                 |              |
   |── Pilih metode ──→|                 |                 |              |
   |   (QRIS /         |                 |                 |              |
   |   Transfer BCA /  |                 |                 |              |
   |   PayPal)         |                 |                 |              |
   |                    |                 |                 |              |
   |── Klik "Konfirmasi|                 |                 |              |
   |    Pembayaran"    |                 |                 |              |
   |                    |                 |                 |              |
   |                    |── validasi ────→|                 |              |
   |                    |   1. metode     |                 |              |
   |                    |      wajib diisi|                 |              |
   |                    |   2. sewa milik |── cek ─────────→|              |
   |                    |      user?      |   penyewa_id    |              |
   |                    |                 |← sah ──────────|              |
   |                    |                 |                 |              |
   |                    |── create() ────────────────────→|              |
   |                    |   sewa_id                        |              |
   |                    |   metode                         |              |
   |                    |   jumlah (grand_total)           |              |
   |                    |   status='lunas'                 |              |
   |                    |                 |                 |── INSERT ───→|
   |                    |← ok ────────────────────────────|              |
   |                    |                 |                 |              |
   |← redirect ────────|                 |                 |              |
   |  /account/struk/  |                 |                 |              |
   |   {sewaId}        |                 |                 |              |
```

---

## 3. Setelah Pembayaran (Status Sewa)

```
Penyewa             Sewa           Pembayaran         Pemilik
   |                 |                 |                 |
   |── Lihat struk ─→|                 |                 |
   |   /account/struk|                 |                 |
   |   /{sewaId}     |                 |                 |
   |                 |── load ────────→|                 |
   |                 |   + pembayaran   |                 |
   |                 |← data ─────────|                 |
   |                 |                 |                 |
   |← Status:        |                 |                 |
   |   "Menunggu     |                 |                 |
   |   Konfirmasi    |                 |                 |
   |   Pemilik"      |                 |                 |
   |                 |                 |                 |
   |                 |                 |                 |
   |                 |                 |   [beberapa waktu kemudian]
   |                 |                 |                 |
   |                 |                 |── confirm() ───→|
   |                 |   (atau reject())                 |
   |                 |                 |                 |
   |                 |── update ───────|                 |
   |                 |   status='aktif'|                 |
   |                 |   disetujui_pada                 |
   |                 |                 |                 |
   |── Lihat status -→|                 |                 |
   |   "Aktif"       |                 |                 |
```

---

## Ringkasan Entity & Relasi

| Entity   | Peran dalam Flow                        | Status Kunci                      |
|----------|-----------------------------------------|-----------------------------------|
| Properti | Sumber informasi & cek ketersediaan     | —                                 |
| Sewa     | Penampung data booking & status sewa    | `pending` → `aktif` / `dibatalkan` |
| Pembayaran | Catatan pembayaran (simulasi lunas)   | `lunas` (langsung saat konfirmasi) |
| Pemilik  | Aktor yang mengonfirmasi/menolak sewa   | —                                 |
