<?php
$judul = "Beranda";
include 'navbar_user.php';
include 'koneksi.php';

// ambil mobil yang tersedia, maksimal 6
$mobil = mysqli_query($koneksi, "SELECT * FROM mobil WHERE status = 'tersedia' LIMIT 6");
$total_mobil = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM mobil WHERE status = 'tersedia'"));

// cek sewa aktif user ini
$sewa_aktif = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM penyewaan WHERE user_id = '{$_SESSION['user_id']}' AND status IN ('pending','disetujui','aktif')");
$data_aktif = mysqli_fetch_assoc($sewa_aktif);
?>

<div class="hero">
    <h1>Selamat Datang, <span><?= $_SESSION['nama'] ?></span>!</h1>
    <p>Sewa mobil mudah, cepat, dan terpercaya. Pilih dari koleksi mobil terbaik kami.</p>
    <a href="daftar_mobil.php" class="btn-hero">Lihat Semua Mobil</a>
</div>

<div class="container">

    <?php if ($data_aktif['jml'] > 0): ?>
    <div class="alert alert-info">
        Kamu punya <strong><?= $data_aktif['jml'] ?></strong> penyewaan aktif. 
        <a href="riwayat_sewa.php">Lihat riwayat</a>
    </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-10">
        <div>
            <div class="page-title">Mobil Tersedia</div>
            <div class="page-sub"><?= $total_mobil ?> mobil siap disewa</div>
        </div>
        <a href="daftar_mobil.php" class="btn btn-abu">Lihat Semua</a>
    </div>

    <div class="grid-mobil">
        <?php while($m = mysqli_fetch_assoc($mobil)): ?>
        <div class="kartu-mobil">
            <div class="foto-mobil">
                <?php if ($m['foto']): ?>
                    <img src="uploads/<?= $m['foto'] ?>" alt="<?= $m['nama'] ?>">
                <?php else: ?>
                    🚗
                <?php endif; ?>
            </div>
            <div class="isi-kartu">
                <div class="nama-mobil"><?= $m['nama'] ?></div>
                <div class="info-kecil">
                    <span class="badge">📅 <?= $m['tahun'] ?></span>
                    <span class="badge">⚙️ <?= $m['transmisi'] ?></span>
                    <span class="badge">👥 <?= $m['kapasitas'] ?> kursi</span>
                </div>
                <div class="harga">
                    Rp <?= number_format($m['harga_per_hari'], 0, ',', '.') ?>
                    <span>/ hari</span>
                </div>
                <a href="detail_mobil.php?id=<?= $m['id'] ?>" class="btn btn-biru btn-full">Sewa Sekarang</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

</div>
</body>
</html>
