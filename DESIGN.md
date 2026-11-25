# DESIGN.md — Sistem Desain Pytricity

Dokumen ini adalah spesifikasi desain UI/UX utama (*canonical design guide*) untuk **Pytricity**. Arah desain mengadopsi estetika **SaaS Finansial / Fintech modern** (merujuk pada struktur desain **Comfra / BudgetControl**).

> [!NOTE]
> Berkas ini melengkapi dan menjadi rujukan utama bersama [docs/DESIGN_SYSTEM.md](docs/DESIGN_SYSTEM.md). Token warna, tipografi, dan komponen spesifik dari referensi **Comfra / BudgetControl** akan difinalisasi sebelum fase implementasi `/frontend`.

---

## 1. Filosofi Visual
- **Clean & Trustworthy:** Menggunakan latar netral terang (*light neutral surface*) dengan kartu berbatas tipis (*subtle 1px border*) agar data tagihan listrik dan nominal Rupiah mudah dipindai.
- **Financial Hierarchy:** Seluruh nominal uang wajib menggunakan *tabular numerals* (`tabular-nums`) dan format `Rp 147.500` tanpa angka desimal `.00` yang mengganggu keterbacaan.
- **Standard App UX:** Halaman autentikasi (`/login`, `/register`, `/admin/login`) bersih tanpa tombol *shortcut* pengisian akun demo.

---

## 2. Token Warna Semantik

| Peran Warna | Kegunaan |
| :--- | :--- |
| **Primary Brand** | Aksi utama (*Cek Tagihan*, *Bayar Sekarang*, *Simpan ID*), tautan aktif sidebar. |
| **Surface / Card** | Latar belakang kartu putih bersih dengan border abu-abu halus. |
| **Status: Paid / Success** | Lencana hijau (*Emerald*) untuk transaksi lunas. |
| **Status: Pending** | Lencana kuning/amber untuk transaksi menunggu pembayaran. |
| **Status: Expired** | Lencana abu-abu netral untuk transaksi kedaluwarsa. |
| **Status: Failed** | Lencana merah (*Rose*) untuk transaksi gagal atau pesan error form. |

---

## 3. Struktur Halaman & Komponen Kunci

### 3.1 Public Landing (`/`)
- **Navbar Sticky:** Brand logo, navigasi seksi (`Beranda`, `Tentang`, `Layanan`, `Kontak`), serta tombol `Masuk` & `Daftar`.
- **Hero:** *Value proposition* jelas + CTA **"Cek Tagihan Sekarang"** (mengarahkan ke `/login` atau `/dashboard` jika sudah terautentikasi).

### 3.2 Area Pelanggan (`(customer)`)
- **App Shell:** Sidebar kiri responsif + Header atas (judul halaman, nama pengguna, tombol keluar).
- **`/dashboard`:** Layout 2 kolom — Panel Cek Tagihan (input 12 digit + *Quick Pick* ID Favorit) di kiri, Panel Rincian Tagihan & tombol **Bayar Sekarang** (Midtrans Snap) di kanan.
- **`/transactions`:** Tabel riwayat transaksi + tombol **Lihat Struk** untuk menampilkan modal/halaman struk digital (`pln_receipt_ref`).
- **`/saved-ids`:** Manajemen CRUD ID Pelanggan Favorit (Rumah, Kantor, Properti).

### 3.3 Area Administrator (`/admin`)
- **KPI Summary Cards:** Total Pendapatan, Transaksi Berhasil, Pelanggan Terdaftar.
- **Data Tables (`/admin/transactions`, `/admin/users`):** Dilengkapi filter status/peran, pencarian, *loading state*, *empty state*, dan paginasi.
