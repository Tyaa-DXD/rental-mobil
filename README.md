# 🚗 RentalKu - Web Rental Mobil

Aplikasi web rental mobil sederhana pakai PHP murni + CSS vanilla.

## Cara Install

### 1. Siapkan Database
- Buka phpMyAdmin
- Buat database baru: `rental_mobil`
- Import file `database.sql`

### 2. Setting Koneksi
Buka file `koneksi.php`, sesuaikan:
```php
$host = "localhost";
$user = "root";
$pass = "";        // isi password MySQL kamu
$db   = "rental_mobil";
```

### 3. Upload ke XAMPP
- Copy semua file ke folder: `C:/xampp/htdocs/rental_mobil/`
- Pastikan semua file ada di 1 folder, tidak ada subfolder
- Kecuali folder `uploads/` yang akan dibuat otomatis

### 4. Buat Akun Admin
- Buka browser: `http://localhost/rental_mobil/setup.php`
- Admin otomatis dibuat dengan:
  - Email: admin@rental.com
  - Password: admin123
- **Hapus file `setup.php` setelah selesai!**

### 5. Siap Dipakai!
- Halaman utama: `http://localhost/rental_mobil/login.php`

---

## Daftar File

| File | Fungsi |
|------|--------|
| `koneksi.php` | Koneksi ke database |
| `login.php` | Halaman login |
| `register.php` | Daftar akun baru |
| `logout.php` | Keluar akun |
| `style.css` | Tampilan CSS semua halaman |
| `navbar_user.php` | Header navigasi user |
| `navbar_admin.php` | Header + sidebar admin |
| `index.php` | Beranda user |
| `daftar_mobil.php` | Daftar semua mobil |
| `detail_mobil.php` | Detail mobil + form sewa |
| `detail_sewa.php` | Detail sewa + form bayar |
| `riwayat_sewa.php` | Riwayat semua sewa user |
| `profil.php` | Profil & edit data user |
| `admin_dashboard.php` | Dashboard admin |
| `admin_mobil.php` | Kelola data mobil |
| `admin_sewa.php` | Lihat semua penyewaan |
| `admin_detail_sewa.php` | Detail + setujui/tolak sewa |
| `admin_pembayaran.php` | Kelola & konfirmasi bayar |
| `admin_users.php` | Data semua user |
| `setup.php` | Setup awal (hapus setelah pakai) |
| `database.sql` | SQL struktur + data awal |

---

## Alur Penggunaan

### User:
1. Daftar akun → login
2. Lihat daftar mobil
3. Klik mobil → isi form sewa (tanggal mulai & selesai)
4. Tunggu disetujui admin
5. Upload bukti pembayaran
6. Tunggu konfirmasi admin
7. Sewa aktif!

### Admin:
1. Login dengan akun admin
2. Lihat dashboard (ada notif pending)
3. Buka "Pengajuan Masuk" → setujui atau tolak
4. Konfirmasi pembayaran di menu Pembayaran
5. Aktifkan sewa kalau mobil sudah diambil
6. Selesaikan sewa kalau mobil sudah dikembalikan
