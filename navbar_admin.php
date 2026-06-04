<?php
// file ini di include di setiap halaman admin
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($judul) ? $judul . ' - Admin RentalKu' : 'Admin RentalKu' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <a href="admin_dashboard.php" class="logo">Rental<span>Ku</span> <span style="font-size:12px; background:var(--kuning); color:white; padding:2px 8px; border-radius:4px; font-weight:600; margin-left:6px">ADMIN</span></a>
    <div class="nav-links">
        <span style="color:#94a3b8; font-size:13px">Halo, <?= $_SESSION['nama'] ?></span>
        <a href="logout.php" class="btn-logout">Keluar</a>
    </div>
</nav>

<div class="layout-admin">
    <div class="sidebar">
        <div class="sidebar-judul">Menu Utama</div>
        <a href="admin_dashboard.php" <?= basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'class="aktif"' : '' ?>>
            📊 Dashboard
        </a>
        <a href="admin_mobil.php" <?= basename($_SERVER['PHP_SELF']) == 'admin_mobil.php' ? 'class="aktif"' : '' ?>>
            🚗 Data Mobil
        </a>

        <div class="sidebar-judul">Penyewaan</div>
        <a href="admin_sewa.php" <?= basename($_SERVER['PHP_SELF']) == 'admin_sewa.php' ? 'class="aktif"' : '' ?>>
            📋 Semua Penyewaan
        </a>
        <a href="admin_sewa.php?status=pending" <?= (basename($_SERVER['PHP_SELF']) == 'admin_sewa.php' && isset($_GET['status']) && $_GET['status'] == 'pending') ? 'class="aktif"' : '' ?>>
            ⏳ Pengajuan Masuk
        </a>
        <a href="admin_pembayaran.php" <?= basename($_SERVER['PHP_SELF']) == 'admin_pembayaran.php' ? 'class="aktif"' : '' ?>>
            💰 Pembayaran
        </a>

        <div class="sidebar-judul">Lainnya</div>
        <a href="admin_users.php" <?= basename($_SERVER['PHP_SELF']) == 'admin_users.php' ? 'class="aktif"' : '' ?>>
            👥 Data User
        </a>
    </div>

    <div class="konten-admin">
