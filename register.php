<?php
include 'koneksi.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$pesan_error = "";

if ($_POST) {
    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $konfirm  = $_POST['konfirm_password'];
    $no_hp    = $_POST['no_hp'];

    // cek password sama
    if ($password != $konfirm) {
        $pesan_error = "Password dan konfirmasi password tidak sama!";
    } else {
        // cek email sudah ada belum
        $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($cek) > 0) {
            $pesan_error = "Email sudah dipakai, coba email lain!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($koneksi, "INSERT INTO users (nama, email, password, no_hp) VALUES ('$nama', '$email', '$hash', '$no_hp')");
            header("Location: login.php?daftar=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - RentalKu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="halaman-auth">
    <div class="kotak-auth" style="max-width:480px">
        <div class="logo-auth">Rental<span>Ku</span></div>
        <div class="subjudul">Buat akun baru</div>

        <?php if ($pesan_error): ?>
            <div class="alert alert-error"><?= $pesan_error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Nama kamu" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@kamu.com" required>
                </div>
                <div class="form-group">
                    <label>No HP</label>
                    <input type="text" name="no_hp" placeholder="08xxxxxxxxxx">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Min 6 karakter" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="konfirm_password" placeholder="Ulangi password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-biru btn-full">Daftar Sekarang</button>
        </form>

        <p class="text-center mt-10 teks-kecil">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </p>
    </div>
</div>
</body>
</html>
