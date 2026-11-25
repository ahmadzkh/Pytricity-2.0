# AGENTS.md

Instruksi operasional standar untuk seluruh AI Coding Agents (*Antigravity, Claude Code, Cursor, GitHub Copilot, Codex*) yang bekerja pada repositori **Pytricity-2.0**.

---

## 1. Konteks & Batasan Ruang Lingkup (MVP)
- **Target Repositori:** `ahmadzkh/Pytricity-2.0` (Headless Monorepo: `/backend` + `/frontend`).
- **Aktor Aktif (MVP):** `customer` (B2C) dan `admin` (Superuser).
- **Aktor Ditunda (Phase 2):** `partner` (`wallets`, `wallet_mutations`, `/partner/*`). **Jangan** membuat rute, migrasi, atau UI untuk `partner` kecuali diminta secara eksplisit.
- **Mode Biller & Pembayaran:** *Deterministic Mock PLN Biller* via `App\Contracts\BillerInterface` + *Midtrans Snap Sandbox*.

---

## 2. Konvensi & Standar Kode

### Backend (`/backend`)
- **Bahasa & Framework:** PHP 8.3+, Laravel 11/12, PSR-12 (Laravel Pint).
- **Database:** PostgreSQL. Gunakan migrasi eksplisit dengan *foreign keys* dan *check constraints* yang ketat.
- **Format API:** Seluruh endpoint wajib mengembalikan amplop JSON standar sesuai `docs/API_SPEC.md`:
  `{ "success": bool, "message": string, "data": mixed, "errors"?: object }`.
- **Keamanan Finansial:**
  - *Inquiry* menyimpan *quote* di server (`inquiry_ref`, TTL 15 menit). *Payment* hanya menerima `inquiry_ref`.
  - Validasi *webhook* Midtrans menggunakan `config('services.midtrans.server_key')`, bukan `env()`.
  - Pastikan *webhook* bersifat idempoten (tidak menimpa `pln_receipt_ref` bila status sudah `paid`).
- **Autentikasi:** Token Sanctum memiliki `expiration: 1440` menit; `POST /api/logout` wajib mencabut token dari basis data; `POST /api/login` wajib memiliki *rate limiter*.

### Frontend (`/frontend`)
- **Bahasa & Framework:** Next.js App Router, React 19, TypeScript (`strict: true`), Tailwind CSS v4.
- **HTTP Client:** Gunakan instance terpusat `lib/api.ts` (Axios + Bearer token interceptor). Jangan mengulang header `Authorization` secara manual di komponen halaman.
- **Type Safety:** `Transaction.id` bertipe `string` (UUID). Seluruh kolom `decimal` dari backend wajib dikonversi dengan `Number(...)` melalui helper `formatRupiah()`.
- **Proteksi Rute:** Validasi sesi pada layout harus bersifat *non-destructive* (redirect ke dashboard sesuai peran jika salah area; hanya hapus token jika respons `401`).

---

## 3. Definisi Selesai (*Definition of Done*)
Setiap perubahan kode dianggap selesai hanya jika:
1. Seluruh dokumen terkait (`docs/API_SPEC.md`, `docs/DATABASE_SCHEMA.md`, `CHANGELOG.md`) tetap sinkron.
2. Backend lolos `php artisan test` (mencakup skenario sukses, gagal validasi, otorisasi peran, dan *webhook signature*).
3. Frontend lolos `npm run lint`, `npx tsc --noEmit`, dan `npm run build` tanpa galat.
