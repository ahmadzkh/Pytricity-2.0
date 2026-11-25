# Panduan Deployment & Infrastruktur — Pytricity

Dokumen ini mengatur standar konfigurasi lingkungan lokal (*Docker Compose*), *deployment* produksi (**Vercel** untuk Frontend + **Dockerized Backend**), integrasi Midtrans Sandbox, serta alur **CI/CD**.

---

## 1. Variabel Lingkungan (*Environment Variables*)

### 1.1 Backend (`/backend/.env`)
```env
APP_NAME=Pytricity
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=pytricity_db
DB_USERNAME=postgres
DB_PASSWORD=secret

FRONTEND_URL=http://localhost:3000

MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 1.2 Frontend (`/frontend/.env.local`)
```env
NEXT_PUBLIC_API_URL=http://127.0.0.1:8000/api
NEXT_PUBLIC_MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
NEXT_PUBLIC_MIDTRANS_SNAP_URL=https://app.sandbox.midtrans.com/snap/snap.js
```

> [!CAUTION]
> **Jangan pernah** menaruh file `.env` atau `.env.local` ke dalam Git. Pada repositori `Pytricity-2.0` versi lama (2023), file `.env` dan folder `vendor/` sempat ter-commit; pembersihan riwayat/tracking dan rotasi `APP_KEY` wajib dilakukan saat fase *setup* repo baru.

---

## 2. Menjalankan Secara Lokal

### Opsi A: Menggunakan Docker Compose (Disarankan)
```bash
# 1. Salin file environment
cp backend/.env.example backend/.env
cp frontend/.env.example frontend/.env.local

# 2. Jalankan seluruh layanan (PostgreSQL, Backend, Frontend)
docker compose up -d --build

# 3. Jalankan migrasi dan seeder akun demo
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate --seed
```

### Opsi B: Tanpa Docker (Manual)
1. **Backend:** Pastikan PHP 8.3, Composer, dan PostgreSQL aktif -> `composer install` -> `php artisan migrate --seed` -> `php artisan serve`.
2. **Frontend:** Pastikan Node.js 22+ aktif -> `npm install` -> `npm run dev`.

---

## 3. Arsitektur Deployment Produksi

### 3.1 Frontend (Vercel)
- **Root Directory:** `frontend`
- **Build Command:** `npm run build`
- **Environment Variables di Vercel Dashboard:**
  - `NEXT_PUBLIC_API_URL=https://api.domain-anda.com/api`
  - `NEXT_PUBLIC_MIDTRANS_CLIENT_KEY=<Midtrans Sandbox Client Key>`
  - `NEXT_PUBLIC_MIDTRANS_SNAP_URL=https://app.sandbox.midtrans.com/snap/snap.js`

### 3.2 Backend (Dockerized — Render / Back4App / Railway / VPS)
- **Root Directory:** `backend`
- **Standar Dockerfile Produksi:**
  - Menggunakan *non-root user* (hindari `chmod -R 777`).
  - Menjalankan server HTTP produksi (`php-fpm` + Nginx, FrankenPHP, atau Octane) — bukan `php artisan serve`.
  - Menyertakan `.dockerignore` agar `vendor/`, `.env`, dan `storage/logs/*` tidak ikut tersalin.
  - Jalankan `php artisan config:cache` dan `php artisan route:cache` saat *build/startup* (aman karena controller tidak memanggil `env()` langsung).
- **CORS:** Konfigurasikan `FRONTEND_URL` pada `backend/config/cors.php` agar hanya mengizinkan domain Vercel produksi & `localhost:3000`.

### 3.3 Konfigurasi Webhook Midtrans Sandbox
1. Masuk ke **Midtrans Sandbox Dashboard** -> **Settings** -> **Configuration**.
2. Isi **Payment Notification URL** dengan:
   ```text
   https://<domain-backend-anda>/api/midtrans/callback
   ```
3. Pastikan metode HTTP adalah `POST` dan aplikasi backend dapat diakses secara publik melalui HTTPS.

---

## 4. Pipeline CI/CD (GitHub Actions)

Setiap *Pull Request* dan *push* ke cabang `main` wajib melewati pemeriksaan otomatis berikut:
1. **Backend CI:**
   - Setup PHP 8.3 + PostgreSQL service container.
   - `composer install --no-interaction --prefer-dist`
   - `php artisan test` (menguji *Authentication*, *Inquiry Quote Lock*, *Payment Creation*, *Midtrans SHA-512 Webhook Signature & Idempotency*, *Saved Customer IDs*, dan *Admin RBAC*).
2. **Frontend CI:**
   - Setup Node.js 22.
   - `npm ci`
   - `npm run lint` (ESLint)
   - `npx tsc --noEmit` (TypeScript strict check)
   - `npm run build` (Next.js production build verification)
