<?php
// file ini di include di setiap halaman user
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] == 'admin') {
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul) ? $judul . ' - RentalKu' : 'RentalKu' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar">
    <a href="index.php" class="logo">Rental<span>Ku</span></a>
    <div class="nav-links">
        <a href="index.php" <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'class="aktif"' : '' ?>>Beranda</a>
        <a href="daftar_mobil.php" <?= basename($_SERVER['PHP_SELF']) == 'daftar_mobil.php' ? 'class="aktif"' : '' ?>>Mobil</a>
        <a href="riwayat_sewa.php" <?= basename($_SERVER['PHP_SELF']) == 'riwayat_sewa.php' ? 'class="aktif"' : '' ?>>Riwayat</a>
        <a href="profil.php" <?= basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'class="aktif"' : '' ?>>Profil</a>
        <a href="logout.php" class="btn-logout">Keluar</a>
    </div>
</nav>
