<?php
include 'koneksi.php';
session_start();

// kalau sudah login langsung lempar ke dashboard
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$pesan_error = "";

if ($_POST) {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $cari = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
    $data = mysqli_fetch_assoc($cari);

    if ($data && password_verify($password, $data['password'])) {
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['nama']    = $data['nama'];
        $_SESSION['email']   = $data['email'];
        $_SESSION['role']    = $data['role'];

        if ($data['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $pesan_error = "Email atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RentalKu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="halaman-auth">
    <div class="kotak-auth">
        <div class="logo-auth">Rental<span>Ku</span></div>
        <div class="subjudul">Masuk ke akun kamu</div>

        <?php if ($pesan_error): ?>
            <div class="alert alert-error"><?= $pesan_error ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['daftar'])): ?>
            <div class="alert alert-sukses">Daftar berhasil! Silahkan login.</div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="contoh@email.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Password kamu" required>
            </div>
            <button type="submit" class="btn btn-biru btn-full">Masuk</button>
        </form>

        <p class="text-center mt-10 teks-kecil">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </p>
    </div>
</div>
</body>
</html>
