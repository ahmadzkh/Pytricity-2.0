# CLAUDE.md

Panduan kerja untuk **Claude Code** dan asisten AI saat mengembangkan repositori **Pytricity-2.0**.

## Ringkasan Proyek
**Pytricity** adalah aplikasi web **PPOB Listrik Pascabayar** berarsitektur **Headless Monorepo** (`/backend` + `/frontend`).
- **Backend (`/backend`):** PHP 8.3, Laravel 11/12, Laravel Sanctum, PostgreSQL, `midtrans/midtrans-php`.
- **Frontend (`/frontend`):** Next.js (App Router), React 19, TypeScript (Strict), Tailwind CSS v4, Axios.
- **Ruang Lingkup MVP:** Area Publik (`/`), Autentikasi (`/login`, `/register`, `/admin/login`), Area Pelanggan (`customer`: `/dashboard`, `/transactions`, `/saved-ids`), dan Area Administrator (`admin`: `/admin`, `/admin/transactions`, `/admin/users`). Modul Mitra/Agen (`partner`) ditunda ke Fase 2.

## Sumber Kebenaran (*Single Source of Truth*)
Sebelum menulis atau mengubah kode, selalu rujuk dokumen berikut:
- `AGENTS.md` — Aturan perilaku agen, batasan keamanan, dan konvensi kode.
- `DESIGN.md` — Standar desain UI/UX, token visual, dan aturan komponen.
- `docs/ARCHITECTURE.md` — *Biller Adapter Pattern*, *Inquiry Quote Lock*, dan alur *webhook*.
- `docs/API_SPEC.md` — Kontrak endpoint REST API dan amplop JSON standar.
- `docs/DATABASE_SCHEMA.md` — Skema PostgreSQL, indeks, *constraints*, dan *seeder*.
- `docs/DEPLOYMENT.md` — Konfigurasi `.env`, Docker, Vercel, dan GitHub Actions CI.

## Perintah Umum
```bash
# Backend (/backend)
composer test                  # atau: php artisan test
php artisan migrate --seed
./vendor/bin/pint --test

# Frontend (/frontend)
npm run lint
npx tsc --noEmit
npm run build
```

## Aturan Kritis
1. **Dilarang memanggil `env()` di luar `config/*.php`** (gunakan `config('services.midtrans.*')`).
2. **Dilarang mempercayai nominal pembayaran dari klien** — `POST /api/ppob/payment` wajib membaca nominal dari `inquiry_ref` yang tersimpan di server.
3. **Webhook Midtrans wajib idempoten** dan memvalidasi `SHA-512` signature serta `fraud_status == 'accept'` untuk `capture`.
4. **Frontend wajib mengonversi `DECIMAL` string dari Laravel** menggunakan `Number(...)` sebelum diformat ke Rupiah, dan menggunakan `string` (UUID) untuk `Transaction.id`.
5. **Jangan pernah meng-commit `.env`, `.env.local`, `vendor/`, atau `node_modules/`.**
