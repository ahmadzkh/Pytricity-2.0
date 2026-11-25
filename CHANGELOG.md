# Changelog

Seluruh perubahan penting pada proyek **Pytricity** didokumentasikan pada berkas ini mengacu pada format [Keep a Changelog](https://keepachangelog.com/id-ID/1.0.0/).

---

## [Unreleased] — Fase Rebuild & Modernisasi Arsitektur

### Ditambahkan (Standarisasi Dokumentasi & Rancangan Arsitektur)
- Menetapkan arsitektur **Headless Monorepo** (`/backend` Laravel 11/12 + PostgreSQL & `/frontend` Next.js App Router + TypeScript + Tailwind CSS v4).
- Menyusun paket dokumentasi standar industri di direktori `docs/`:
  - `docs/ARCHITECTURE.md` (Biller Adapter Pattern, Quote Lock, Webhook Idempotency, Security).
  - `docs/API_SPEC.md` (Kontrak REST API, Standar Amplop JSON, dan Error Codes).
  - `docs/DATABASE_SCHEMA.md` (ERD PostgreSQL, skema `users`, `transactions`, `saved_customer_ids`, dan `DatabaseSeeder`).
  - `docs/DESIGN_SYSTEM.md` (Panduan UI/UX SaaS Finansial modern mengacu pada desain Comfra / BudgetControl).
  - `docs/DEPLOYMENT.md` (Panduan Docker Compose lokal, Vercel, Docker Backend, dan GitHub Actions CI).
- Menetapkan ruang lingkup MVP yang terfokus pada alur **Customer (B2C)** dan **Administrator (Admin)**, sementara modul **Partner/Agen (B2B2C)** dijadwalkan untuk Fase 2.

---

## [2.0.0] — 2023-01-18 (Legacy Prototype)

### Catatan Historis
- Komit awal prototipe monolitik Laravel 8.83.18 berbasis *template* toko *online* dengan *landing page* bertema PPOB listrik pascabayar (`1000ce3`).
- Akan digantikan sepenuhnya pada proses *rebuild* ke arsitektur *headless* modern.
