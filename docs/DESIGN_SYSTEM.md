# Sistem Desain & Standar UI/UX — Pytricity

Dokumen ini menetapkan standar antarmuka (*UI/UX*) untuk **Pytricity** dengan mengadopsi struktur tata letak dan estetika **SaaS Finansial / Fintech modern** (merujuk pada arah desain **Comfra / BudgetControl**).

---

## 1. Prinsip Desain Utama

1. **Kejelasan Data Finansial (*Financial Clarity*):** Angka tagihan, biaya admin, dan total pembayaran harus memiliki hierarki visual yang tegas menggunakan *tabular numbers* (`tabular-nums`) dan pemformatan mata uang Indonesia (`Rp 147.500`).
2. **Permukaan Bersih & Terstruktur (*Clean Neutral Surface*):** Menghindari warna dekoratif berlebihan. Latar utama menggunakan palet netral terang (*light/slate surface*) dengan kartu berbatas halus (*subtle border*) dan bayangan minimal.
3. **Aksen Status Semantik yang Konsisten:**
   - **Berhasil (`paid` / `success`):** Hijau (*Emerald*).
   - **Menunggu (`pending`):** Kuning/Amber.
   - **Kedaluwarsa (`expired`):** Abu-abu / Oranye redup.
   - **Gagal (`failed`):** Merah (*Rose*).
4. **Standard App UX:** Halaman autentikasi (`/login`, `/admin/login`, `/register`) tampil bersih dan profesional tanpa tombol *shortcut* pengisian kredensial demo di UI.

---

## 2. Arsitektur Tata Letak (*Layout Structure*)

### 2.1 Halaman Publik (`/`)
- **Top Navigation Bar (Sticky):** Logo `Pytricity`, tautan seksi (`Beranda`, `Tentang`, `Layanan`, `Kontak`), serta tombol aksi `Masuk` dan `Daftar`.
- **Hero Section:** Judul bernilai tambah jelas + CTA utama **"Cek Tagihan Sekarang"** (mengarahkan ke `/login` atau langsung `/dashboard` bila sesi aktif) dan CTA sekunder **"Pelajari Fitur"**.
- **Feature & Trust Sections:** Ringkasan kapabilitas (Cek Instan, Multi-Channel Midtrans, Simpan ID Pelanggan).

### 2.2 Workspace Pelanggan (`/dashboard`, `/transactions`, `/saved-ids`)
- **App Shell:** Sidebar navigasi tetap di kiri (responsif menjadi *drawer* di perangkat seluler) + Top Header berisi judul halaman, nama pengguna, dan tombol keluar.
- **Grid 2-Kolom pada `/dashboard`:**
  - Kiri: Panel Input ID Pelanggan 12 digit + daftar *Quick Pick* dari ID Pelanggan Favorit.
  - Kanan: Panel Rincian Tagihan (Nama, Daya, Tagihan Pokok, Biaya Admin, Total) + tombol **"Bayar Sekarang"** yang memicu modal Midtrans Snap.

### 2.3 Workspace Administrator (`/admin/*`)
- **App Shell:** Sidebar navigasi terpisah (`Ikhtisar Sistem`, `Manajemen Transaksi`, `Data Pengguna`).
- **KPI Cards:** 3 kartu metrik sejajar (`Total Pendapatan`, `Transaksi Berhasil`, `Pelanggan Terdaftar`).
- **Data Tables:** Tabel responsif dengan *toolbar* filter status/peran, kolom pencarian, indikator pemuatan (*skeleton/spinner*), *empty state*, dan kontrol paginasi.

---

## 3. Aturan Implementasi Frontend (TypeScript & Tailwind)

1. **Konversi Tipe Angka Desimal:** Karena Laravel mengembalikan tipe `DECIMAL(12,2)` sebagai `string` JSON (mis. `"147500.00"`), seluruh komponen wajib membungkus nilai uang dengan fungsi helper terpusat:
   ```ts
   export function formatRupiah(amount: number | string): string {
     return new Intl.NumberFormat("id-ID", {
       style: "currency",
       currency: "IDR",
       minimumFractionDigits: 0,
     }).format(Number(amount));
   }
   ```
2. **Tipe ID Transaksi:** Interface TypeScript `Transaction.id` wajib bertipe `string` (UUID), bukan `number`.
3. **Proteksi Rute & Interceptor:**
   - Seluruh panggilan API menggunakan `lib/api.ts` (tanpa duplikasi header `Authorization` manual di setiap halaman).
   - Validasi sesi di layout wajib melakukan *non-destructive redirect* sesuai peran dan hanya menghapus token pada status `401`.

---

## 4. Penyesuaian Token Desain Comfra / BudgetControl

> [!NOTE]
> Bagian ini disiapkan sebagai tempat pengisian token warna, tipografi, dan komponen spesifik (mis. palet primer, *border radius*, serta komponen *shadcn/ui* bila digunakan) saat referensi visual **Comfra / BudgetControl** disertakan sebelum fase implementasi UI.
