<?php
$judul = "Dashboard";
include 'navbar_admin.php';
include 'koneksi.php';

// hitung statistik
$total_mobil    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM mobil"))['jml'];
$mobil_tersedia = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM mobil WHERE status='tersedia'"))['jml'];
$total_user     = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM users WHERE role='user'"))['jml'];
$total_sewa     = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM penyewaan"))['jml'];
$sewa_pending   = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM penyewaan WHERE status='pending'"))['jml'];
$bayar_menunggu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pembayaran WHERE status='menunggu'"))['jml'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(jumlah) as total FROM pembayaran WHERE status='dikonfirmasi'"))['total'];
?>

<div class="page-title">Dashboard Admin</div>
<div class="page-sub">Selamat datang, <?= $_SESSION['nama'] ?>!</div>

<!-- Statistik -->
<div class="grid-stat">
    <div class="kartu-stat" style="border-left-color:#2563eb">
        <div class="angka"><?= $total_mobil ?></div>
        <div class="label">🚗 Total Mobil</div>
    </div>
    <div class="kartu-stat" style="border-left-color:#16a34a">
        <div class="angka"><?= $mobil_tersedia ?></div>
        <div class="label">✅ Mobil Tersedia</div>
    </div>
    <div class="kartu-stat" style="border-left-color:#f59e0b">
        <div class="angka"><?= $total_user ?></div>
        <div class="label">👥 Total User</div>
    </div>
    <div class="kartu-stat" style="border-left-color:#8b5cf6">
        <div class="angka"><?= $total_sewa ?></div>
        <div class="label">📋 Total Sewa</div>
    </div>
    <div class="kartu-stat" style="border-left-color:#dc2626">
        <div class="angka"><?= $sewa_pending ?></div>
        <div class="label">⏳ Pengajuan Pending</div>
    </div>
    <div class="kartu-stat" style="border-left-color:#0891b2">
        <div class="angka">Rp <?= number_format($total_pendapatan ?? 0, 0, ',', '.') ?></div>
        <div class="label">💰 Total Pendapatan</div>
    </div>
</div>

<!-- Notifikasi penting -->
<?php if ($sewa_pending > 0): ?>
<div class="alert alert-info">
    Ada <strong><?= $sewa_pending ?></strong> pengajuan penyewaan yang menunggu persetujuan.
    <a href="admin_sewa.php?status=pending">Lihat sekarang →</a>
</div>
<?php endif; ?>

<?php if ($bayar_menunggu > 0): ?>
<div class="alert alert-info">
    Ada <strong><?= $bayar_menunggu ?></strong> pembayaran yang menunggu konfirmasi.
    <a href="admin_pembayaran.php?status=menunggu">Lihat sekarang →</a>
</div>
<?php endif; ?>

<!-- Penyewaan terbaru -->
<div class="kotak">
    <div class="kotak-judul">Penyewaan Terbaru</div>
    <div class="tabel-wrap">
        <?php
        $terbaru = mysqli_query($koneksi, "SELECT p.*, u.nama as nama_user, m.nama as nama_mobil FROM penyewaan p JOIN users u ON p.user_id = u.id JOIN mobil m ON p.mobil_id = m.id ORDER BY p.created_at DESC LIMIT 8");
        ?>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>User</th>
                    <th>Mobil</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php while($s = mysqli_fetch_assoc($terbaru)): ?>
                <tr>
                    <td class="teks-kecil bold"><?= $s['kode_sewa'] ?></td>
                    <td><?= $s['nama_user'] ?></td>
                    <td><?= $s['nama_mobil'] ?></td>
                    <td class="teks-kecil"><?= date('d M Y', strtotime($s['tanggal_mulai'])) ?></td>
                    <td>Rp <?= number_format($s['total_harga'], 0, ',', '.') ?></td>
                    <td><span class="status status-<?= $s['status'] ?>"><?= ucfirst($s['status']) ?></span></td>
                    <td><a href="admin_detail_sewa.php?id=<?= $s['id'] ?>" class="btn btn-kecil btn-biru">Detail</a></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

    </div><!-- end konten-admin -->
</div><!-- end layout-admin -->
</body>
</html>
