# Spesifikasi REST API — Pytricity

Base URL Lokal: `http://127.0.0.1:8000/api`  
Header Wajib pada Request Terproteksi:
```http
Accept: application/json
Content-Type: application/json
Authorization: Bearer <access_token>
```

---

## 1. Standar Amplop Respons (*Response Envelope*)

Seluruh *endpoint* wajib menggunakan format respons JSON yang seragam:

**Sukses:**
```json
{
  "success": true,
  "message": "Pesan deskriptif singkat.",
  "data": {}
}
```

**Gagal / Validasi:**
```json
{
  "success": false,
  "message": "Ringkasan kesalahan.",
  "errors": {
    "field_name": ["Detail pesan validasi."]
  }
}
```

### Kode Status HTTP Standar
- `200 OK`: Permintaan berhasil.
- `201 Created`: Sumber daya baru berhasil dibuat (Registrasi, Pembayaran, Simpan ID).
- `401 Unauthorized`: Token tidak ada, tidak valid, atau kedaluwarsa.
- `403 Forbidden`: Peran pengguna tidak memiliki otoritas (atau *signature webhook* tidak valid).
- `404 Not Found`: Data transaksi atau ID pelanggan PLN tidak ditemukan.
- `422 Unprocessable Entity`: Kesalahan validasi input atau *inquiry quote* sudah kedaluwarsa.
- `429 Too Many Requests`: Melebihi batas percobaan (*rate limit* login).

---

## 2. Endpoint Autentikasi (`/auth`)

### `POST /api/register`
Mendaftarkan pengguna baru dengan peran `customer`.
- **Auth:** Publik
- **Request Body:**
```json
{
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "password": "Password123!",
  "password_confirmation": "Password123!"
}
```
- **Response `201`:** Mengembalikan objek `user` (`id`, `name`, `email`, `role`) dan `access_token`.

### `POST /api/login`
Memvalidasi kredensial dan menerbitkan token Sanctum (dibatasi 5 permintaan/menit).
- **Auth:** Publik
- **Request Body:**
```json
{
  "email": "budi@example.com",
  "password": "Password123!"
}
```
- **Response `200`:**
```json
{
  "success": true,
  "message": "Login berhasil.",
  "data": {
    "user": { "id": 1, "name": "Budi Santoso", "email": "budi@example.com", "role": "customer" },
    "access_token": "1|abcdef...",
    "token_type": "Bearer"
  }
}
```

### `POST /api/logout`
Mencabut token akses yang sedang aktif dari basis data.
- **Auth:** `auth:sanctum`
- **Response `200`:** `{ "success": true, "message": "Berhasil keluar sistem.", "data": null }`

### `GET /api/user`
Mengambil profil pengguna yang sedang masuk.
- **Auth:** `auth:sanctum`
- **Response `200`:** `{ "success": true, "message": "Profil pengguna.", "data": { "id": 1, "name": "...", "email": "...", "role": "customer" } }`

---

## 3. Endpoint PPOB Pelanggan (`/ppob`)

### `POST /api/ppob/inquiry`
Memeriksa tagihan listrik pascabayar dan mengunci *quote* di server selama 15 menit.
- **Auth:** `auth:sanctum`
- **Request Body:**
```json
{
  "customer_number": "111111111111"
}
```
- **Validasi:** `required|string|digits:12`
- **Response `200`:**
```json
{
  "success": true,
  "message": "Tagihan ditemukan.",
  "data": {
    "inquiry_ref": "INQ-9f8e7d6c-...",
    "customer_number": "111111111111",
    "customer_name": "Andi Wijaya",
    "power": "2200 VA",
    "billing_amount": 145000,
    "admin_fee": 2500,
    "total_amount": 147500,
    "expires_at": "2026-11-24T22:45:00Z"
  }
}
```

### `POST /api/ppob/payment`
Membuat transaksi berstatus `pending` berdasarkan `inquiry_ref` dari server dan menghasilkan `snap_token` Midtrans.
- **Auth:** `auth:sanctum`
- **Request Body:**
```json
{
  "inquiry_ref": "INQ-9f8e7d6c-..."
}
```
- **Response `201`:**
```json
{
  "success": true,
  "message": "Transaksi berhasil dibuat.",
  "data": {
    "id": "uuid-v4",
    "order_id": "TRX-1732480000-A1B2C",
    "total_amount": 147500,
    "snap_token": "66e4fa55-..."
  }
}
```

### `GET /api/ppob/history`
Mengambil riwayat transaksi milik pengguna yang sedang login (terurut dari terbaru).
- **Auth:** `auth:sanctum`
- **Query Params:** `?page=1&status=all|pending|paid|expired|failed`
- **Response `200`:** Objek paginasi Laravel berisi daftar transaksi (`id`, `order_id`, `customer_number`, `customer_name`, `billing_amount`, `admin_fee`, `total_amount`, `payment_status`, `ppob_status`, `pln_receipt_ref`, `created_at`).

---

## 4. Endpoint ID Pelanggan Favorit (`/saved-customers`)

### `GET /api/saved-customers`
Mengambil daftar ID pelanggan tersimpan milik pengguna aktif.
- **Auth:** `auth:sanctum`

### `POST /api/saved-customers`
Menyimpan ID pelanggan baru.
- **Auth:** `auth:sanctum`
- **Request Body:**
```json
{
  "customer_number": "111111111111",
  "alias_name": "Listrik Rumah Utama"
}
```

### `PUT /api/saved-customers/{id}` & `DELETE /api/saved-customers/{id}`
Memperbarui nama alias atau menghapus ID pelanggan milik pengguna aktif (mengembalikan `403`/`404` jika bukan milik pengguna tersebut).

---

## 5. Endpoint Webhook Midtrans

### `POST /api/midtrans/callback`
Menerima notifikasi status pembayaran asinkron dari Midtrans.
- **Auth:** Publik (Dilindungi verifikasi `SHA-512(order_id + status_code + gross_amount + server_key)`)
- **Perilaku Idempoten:**
  - Jika transaksi sudah berstatus `paid`, langsung kembalikan `200 OK` tanpa menimpa `pln_receipt_ref`.
  - `capture` (`fraud_status == 'accept'`) / `settlement` -> `payment_status = 'paid'`, `ppob_status = 'success'`, terbitkan `pln_receipt_ref`.
  - `expire` -> `payment_status = 'expired'`, `ppob_status = 'failed'`.
  - `deny` / `cancel` -> `payment_status = 'failed'`, `ppob_status = 'failed'`.

---

## 6. Endpoint Administrator (`/admin`)

Seluruh endpoint di bawah ini wajib melewati middleware `auth:sanctum` dan `IsAdmin`.

### `GET /api/admin/dashboard`
Mengembalikan metrik ringkasan (`revenue`, `success_count`, `user_count` untuk `role = 'customer'`) serta 5 transaksi terbaru.

### `GET /api/admin/transactions`
Mengembalikan daftar seluruh transaksi sistem dengan paginasi.
- **Query Params:** `?page=1&status=all|pending|paid|expired|failed&search=<order_id_or_customer_number>`

### `GET /api/admin/users`
Mengembalikan daftar pengguna dengan paginasi.
- **Query Params:** `?page=1&role=all|customer|admin&search=<name_or_email>`
