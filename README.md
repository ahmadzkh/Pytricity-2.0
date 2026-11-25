# Pytricity — Postpaid Electricity PPOB Platform

![Architecture](https://img.shields.io/badge/Architecture-Headless_Monorepo-0F172A?style=flat-square)
![Backend](https://img.shields.io/badge/Backend-Laravel_11%2F12_%7C_PHP_8.3-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Frontend](https://img.shields.io/badge/Frontend-Next.js_%7C_React_19_%7C_TypeScript-000000?style=flat-square&logo=nextdotjs&logoColor=white)
![Database](https://img.shields.io/badge/Database-PostgreSQL-4169E1?style=flat-square&logo=postgresql&logoColor=white)
![Payment](https://img.shields.io/badge/Payment-Midtrans_Snap_Sandbox-002D72?style=flat-square)

**Pytricity** adalah platform web **Payment Point Online Banking (PPOB)** untuk pengecekan dan pembayaran tagihan listrik pascabayar (PLN) berbasis arsitektur **Headless Monorepo**. Proyek ini dirancang dengan standar produksi yang menekankan keamanan transaksi finansial, konsistensi kontrak API, pengujian otomatis, serta pengalaman pengguna bergaya SaaS Finansial modern.

> [!NOTE]
> Repositori `Pytricity-2.0` sedang dalam tahap **modernisasi menyeluruh** dari prototipe monolitik Laravel 8 (2023) menjadi platform *headless* (`/backend` + `/frontend`). Seluruh spesifikasi teknis pada dokumentasi ini menjadi acuan tunggal (*single source of truth*) implementasi.

---

## Fitur Utama (MVP Scope)

### Area Pelanggan (`customer`)
- **Autentikasi & Manajemen Sesi:** Registrasi, login, dan logout dengan pencabutan token di sisi peladen (*server-side token revocation*).
- **Cek Tagihan Listrik (*Inquiry*):** Pemeriksaan tagihan berdasarkan ID Pelanggan 12 digit (terproteksi autentikasi) dengan *Quote Lock* di server untuk mencegah manipulasi nominal.
- **Pembayaran Multi-Channel:** Integrasi **Midtrans Snap Sandbox** (Virtual Account Bank, QRIS, E-Wallet, dan Minimarket) dengan verifikasi *webhook* SHA-512 yang idempoten.
- **ID Pelanggan Favorit (`Saved Customer IDs`):** Simpan, ubah, dan hapus daftar ID pelanggan (Rumah, Kantor, Properti) untuk pengecekan ulang 1-klik.
- **Riwayat & Struk Digital PLN:** Pantau status transaksi secara *real-time* beserta nomor referensi struk resmi (`pln_receipt_ref`).

### Area Administrator (`admin`)
- **Dashboard Analitik:** Ringkasan total pendapatan, jumlah transaksi berhasil, jumlah pelanggan terdaftar, dan transaksi terbaru.
- **Manajemen Transaksi:** Filter status (`paid`, `pending`, `expired`, `failed`), pencarian, paginasi, dan inspeksi detail struk.
- **Manajemen Pengguna:** Pencarian dan pemantauan daftar akun pengguna terotorisasi.

---

## Tumpukan Teknologi (*Tech Stack*)

| Lapisan | Teknologi | Alasan Pemilihan |
| :--- | :--- | :--- |
| **Backend API** | PHP 8.3, Laravel 11/12, Laravel Sanctum | Struktur ramping, dukungan jangka panjang, token API stateless yang aman. |
| **Database** | PostgreSQL | Integritas transaksi ACID, dukungan `UUID`, `DECIMAL(12,2)`, dan *check constraints*. |
| **Frontend** | Next.js (App Router), React 19, TypeScript, Tailwind CSS v4 | *Type-safe* untuk data finansial, *routing* modular per peran, dan UI responsif. |
| **Payment Gateway** | Midtrans Snap (`midtrans/midtrans-php`) | Simulasi gratis penuh untuk VA Bank, QRIS, E-Wallet, dan gerai retail. |
| **Biller Engine** | `BillerInterface` + Deterministic Mock PLN | Simulasi deterministik 24/7 tanpa ketergantungan API berbayar, siap ditukar ke API nyata. |
| **DevOps & QA** | Docker, Docker Compose, PHPUnit/Pest, ESLint, GitHub Actions | Lingkungan lokal 1-perintah dan jaminan regresi otomatis. |

---

## Struktur Direktori Target

```text
Pytricity-2.0/
├── backend/                 # Laravel RESTful API
│   ├── app/
│   │   ├── Contracts/       # BillerInterface
│   │   ├── Services/        # MockPlnBillerService, MidtransService
│   │   └── Http/
│   └── Dockerfile
├── frontend/                # Next.js App Router (TypeScript)
│   ├── app/
│   │   ├── (public)/        # Landing page, /login, /register
│   │   ├── (customer)/      # /dashboard, /transactions, /saved-ids
│   │   └── admin/           # /admin, /admin/transactions, /admin/users
│   └── lib/
├── docs/                    # Dokumentasi Standar Proyek
│   ├── ARCHITECTURE.md
│   ├── API_SPEC.md
│   ├── DATABASE_SCHEMA.md
│   ├── DESIGN_SYSTEM.md
│   └── DEPLOYMENT.md
├── docker-compose.yml       # Orkestrasi lokal (PostgreSQL + Backend + Frontend)
└── CHANGELOG.md
```

---

## Menjalankan Secara Lokal

Panduan lengkap instalasi lokal (baik menggunakan Docker Compose maupun manual) serta konfigurasi *environment variables* tersedia di **[docs/DEPLOYMENT.md](docs/DEPLOYMENT.md)**.

### Ringkas (Docker Compose)
```bash
git clone https://github.com/ahmadzkh/Pytricity-2.0.git
cd Pytricity-2.0
cp backend/.env.example backend/.env
cp frontend/.env.example frontend/.env.local
docker compose up --build
```

---

## Indeks Dokumentasi

- **[CLAUDE.md](CLAUDE.md)** & **[AGENTS.md](AGENTS.md)** — Panduan aturan kerja, batasan MVP, dan standar QA untuk asisten AI.
- **[DESIGN.md](DESIGN.md)** & **[docs/DESIGN_SYSTEM.md](docs/DESIGN_SYSTEM.md)** — Standar desain UI/UX modern bergaya SaaS Finansial (referensi Comfra / BudgetControl).
- **[Arsitektur Sistem](docs/ARCHITECTURE.md)** — Diagram arsitektur, *Biller Adapter Pattern*, alur *Quote Lock*, dan keamanan *webhook*.
- **[Spesifikasi API](docs/API_SPEC.md)** — Standar amplop JSON, daftar *endpoint*, kode galat, dan kontrak *payload*.
- **[Skema Basis Data](docs/DATABASE_SCHEMA.md)** — ERD, definisi tabel PostgreSQL, indeks, *constraints*, dan *seeder*.
- **[Panduan Deployment](docs/DEPLOYMENT.md)** — Setup lokal, Vercel, Docker Backend, Midtrans Webhook, dan CI/CD.
- **[Catatan Perubahan](CHANGELOG.md)** — Riwayat evolusi dari v2.0 *legacy* ke arsitektur *rebuild*.

---

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
