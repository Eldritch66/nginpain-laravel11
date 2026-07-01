# Tech Stack — Nginapin

live wire dibutuhkan untuk membuat halaman interaktif tanpa javascript

## Backend

| Teknologi             | Versi | Keterangan                                   |
| --------------------- | ----- | -------------------------------------------- |
| **PHP**               | ^8.3  | Runtime                                      |
| **Laravel**           | ^11.0 | Framework                                    |
| **Livewire**          | ^4.3  | Full-stack component (Blade + JS)            |
| **Filament**          | ^5.0  | Admin panel (terinstal, belum dikonfigurasi) |
| **Laravel Socialite** | ^5.28 | Google OAuth login                           |
| **Midtrans PHP SDK**  | ^2.6  | Payment gateway (Snap)                       |

## Frontend

| Teknologi               | Versi | Keterangan                                                   |
| ----------------------- | ----- | ------------------------------------------------------------ |
| **Tailwind CSS**        | ^4.3  | Utility-first CSS (`@import "tailwindcss"`, `@theme inline`) |
| **Vite**                | ^5.0  | Bundler + dev server                                         |
| **laravel-vite-plugin** | ^1.0  | Integrasi Vite + Laravel                                     |
| **@tailwindcss/vite**   | ^4.3  | Tailwind Vite plugin                                         |

> Tailwind v4 — tidak ada `tailwind.config.js` atau `postcss.config.js`. Konfigurasi di `resources/css/app.css` via `@theme inline`.

## Database

| Item               | Detail                              |
| ------------------ | ----------------------------------- |
| **Default**        | SQLite (`database/database.sqlite`) |
| **Production/Env** | MySQL (via `.env`)                  |
| **Driver session** | Database                            |
| **Driver cache**   | Database                            |
| **Driver queue**   | Database                            |

## Testing

| Tools               | Detail                                            |
| ------------------- | ------------------------------------------------- |
| **PHPUnit**         | ^10.5                                             |
| **Feature tests**   | Extend `Tests\TestCase` (Laravel HTTP)            |
| **Unit tests**      | Extend `PHPUnit\Framework\TestCase` (no app boot) |
| **DB**              | SQLite default (migration available)              |
| **RefreshDatabase** | Tidak dipakai                                     |

## Tools & DevOps

| Tool             | Versi | Keterangan                        |
| ---------------- | ----- | --------------------------------- |
| **Laravel Pint** | ^1.13 | Code formatter (PSR-12)           |
| **Laravel Sail** | ^1.26 | Docker dev environment (opsional) |
| **Composer**     | —     | PHP package manager               |
| **npm**          | —     | JS package manager                |
| **Git**          | —     | Version control                   |
| **Laragon**      | —     | Local dev env (Windows)           |

## Struktur Direktori

```
app/
├── Http/
│   └── Controllers/
│       ├── Auth/SocialiteController.php
│       └── MidtransCallbackController.php
├── Livewire/
│   ├── Auth/Register.php, RoleSelect.php
│   ├── Pembayaran/PembayaranForm.php
│   └── Properti/PropertiDetail.php, PropertiList.php
├── Models/
│   ├── User.php, Properti.php, Sewa.php
│   ├── Pembayaran.php, Unit.php
│   └── TiketBantuan.php
└── Services/
    └── MidtransService.php

routes/web.php          — Semua route (closure-based, no API routes)
resources/views/        — Blade templates
resources/css/app.css   — Tailwind v4 config
database/migrations/    — Semua migration
docs/                   — Dokumentasi
```

## Catatan

- **Tidak ada JS framework** (no Vue/React/Alpine)
- **Tidak ada API routes** — semuanya via web routes + Blade + Livewire
- **Auth scaffolding** — custom (no Laravel Breeze/Jetstream)
- **Kode booking** format: `SW-{YYMM}-{NNN}`
- **Kode bayar** format: `PAY-{METHOD}-{NNN}`
