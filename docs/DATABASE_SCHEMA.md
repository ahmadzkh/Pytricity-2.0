# Skema Basis Data — Pytricity (PostgreSQL)

Dokumen ini mendefinisikan struktur relasional PostgreSQL untuk MVP Pytricity serta rancangan perluasan Fase 2.

---

## 1. Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    USERS ||--o{ TRANSACTIONS : "melakukan"
    USERS ||--o{ SAVED_CUSTOMER_IDS : "menyimpan"
    USERS ||--o{ PERSONAL_ACCESS_TOKENS : "memiliki"

    USERS {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        enum role "admin | customer"
        timestamp created_at
        timestamp updated_at
    }

    TRANSACTIONS {
        uuid id PK
        bigint user_id FK
        string order_id UK "TRX-timestamp-random"
        string customer_number "12 digit PLN"
        string customer_name
        string power "e.g. 2200 VA"
        decimal billing_amount "12,2"
        decimal admin_fee "12,2"
        decimal total_amount "12,2"
        string midtrans_snap_token
        enum payment_status "pending | paid | expired | failed"
        enum ppob_status "pending | processing | success | failed"
        string pln_receipt_ref
        timestamp created_at
        timestamp updated_at
    }

    SAVED_CUSTOMER_IDS {
        bigint id PK
        bigint user_id FK
        string customer_number "12 digit PLN"
        string alias_name
        timestamp created_at
        timestamp updated_at
    }
```

---

## 2. Definisi Tabel MVP

### 2.1 `users`
Menyimpan akun pengguna terautentikasi.
- **Primary Key:** `id` (`BIGSERIAL`)
- **Constraints:** `email` `UNIQUE`, `role` `CHECK (role IN ('admin', 'customer'))` dengan *default* `'customer'`.
- **Catatan Migrasi:** Saat modul Partner diaktifkan di Fase 2, *constraint* `role` diperluas untuk menerima `'partner'`.

### 2.2 `transactions`
Menyimpan seluruh catatan transaksi pembayaran tagihan listrik.
- **Primary Key:** `id` (`UUID`)
- **Foreign Key:** `user_id` -> `users(id)` (`ON DELETE CASCADE`)
- **Kolom Kunci:**
  - `order_id` (`VARCHAR(64)`, `UNIQUE`, `NOT NULL`): Referensi unik yang dikirim ke Midtrans (`TRX-...`).
  - `customer_number` (`VARCHAR(12)`, `NOT NULL`): Nomor meter / ID Pelanggan PLN.
  - `customer_name` (`VARCHAR(255)`, `NOT NULL`): Nama pelanggan hasil *inquiry*.
  - `power` (`VARCHAR(32)`, `NOT NULL`): Golongan daya listrik (mis. `2200 VA`).
  - `billing_amount` (`DECIMAL(12,2)`, `NOT NULL`): Nominal tagihan pokok PLN.
  - `admin_fee` (`DECIMAL(12,2)`, `NOT NULL`): Biaya admin platform.
  - `total_amount` (`DECIMAL(12,2)`, `NOT NULL`): `billing_amount + admin_fee`.
  - `midtrans_snap_token` (`VARCHAR(255)`, `NULLABLE`): Token transaksi Midtrans Snap.
  - `payment_status` (`ENUM`: `pending`, `paid`, `expired`, `failed`).
  - `ppob_status` (`ENUM`: `pending`, `processing`, `success`, `failed`).
  - `pln_receipt_ref` (`VARCHAR(64)`, `NULLABLE`): Nomor referensi struk resmi PLN setelah lunas.
- **Indeks Disarankan:** Index pada `(user_id, created_at DESC)`, `payment_status`, dan `order_id`.

### 2.3 `saved_customer_ids`
Menyimpan daftar ID Pelanggan PLN favorit milik masing-masing pengguna.
- **Primary Key:** `id` (`BIGSERIAL`)
- **Foreign Key:** `user_id` -> `users(id)` (`ON DELETE CASCADE`)
- **Constraints:** `UNIQUE (user_id, customer_number)` untuk mencegah duplikasi nomor meter pada akun yang sama.

### 2.4 `personal_access_tokens`
Tabel standar Laravel Sanctum untuk manajemen token Bearer API.

---

## 3. Data Seeder Standar (`DatabaseSeeder`)

Untuk memastikan aplikasi siap didemokan langsung setelah `php artisan migrate --seed`:
1. **Akun Admin Demo:**
   - Email: `admin@pytricity.com` | Role: `admin`
2. **Akun Customer Demo:**
   - Email: `customer@pytricity.com` | Role: `customer`
   - Dilengkapi beberapa data contoh di `saved_customer_ids` dan `transactions` (`paid`, `pending`, `failed`) agar halaman Riwayat Transaksi dan Dashboard Admin langsung terisi secara realistis.

---

## 4. Rancangan Tabel Fase 2 (Non-MVP / Partner Module)

Tabel berikut disiapkan secara konseptual dan **tidak dibuat pada migrasi MVP** sebelum modul Mitra/Agen diaktifkan:
- **`wallets`:** `id`, `user_id` (FK unique), `balance` (`DECIMAL(14,2)`).
- **`wallet_mutations`:** `id`, `wallet_id` (FK), `transaction_id` (FK nullable), `type` (`credit | debit`), `amount` (`DECIMAL(14,2)`), `balance_after` (`DECIMAL(14,2)`), `description`.
