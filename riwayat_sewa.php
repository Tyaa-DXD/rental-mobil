<?php
$judul = "Riwayat Sewa";
include 'navbar_user.php';
include 'koneksi.php';

$user_id = $_SESSION['user_id'];

// filter status
$filter = isset($_GET['status']) ? $_GET['status'] : '';
$where = "WHERE p.user_id = '$user_id'";
if ($filter) $where .= " AND p.status = '$filter'";

$sewaan = mysqli_query($koneksi, "SELECT p.*, m.nama as nama_mobil, m.foto FROM penyewaan p JOIN mobil m ON p.mobil_id = m.id $where ORDER BY p.created_at DESC");
?>

<div class="container">
    <div class="page-title">Riwayat Penyewaan</div>
    <div class="page-sub">Semua penyewaan yang pernah kamu ajukan</div>

    <!-- filter -->
    <div class="flex gap-10 mb-20" style="flex-wrap:wrap">
        <a href="riwayat_sewa.php" class="btn btn-kecil <?= !$filter ? 'btn-biru' : 'btn-abu' ?>">Semua</a>
        <a href="?status=pending" class="btn btn-kecil <?= $filter=='pending' ? 'btn-biru' : 'btn-abu' ?>">Pending</a>
        <a href="?status=disetujui" class="btn btn-kecil <?= $filter=='disetujui' ? 'btn-biru' : 'btn-abu' ?>">Disetujui</a>
        <a href="?status=aktif" class="btn btn-kecil <?= $filter=='aktif' ? 'btn-biru' : 'btn-abu' ?>">Aktif</a>
        <a href="?status=selesai" class="btn btn-kecil <?= $filter=='selesai' ? 'btn-biru' : 'btn-abu' ?>">Selesai</a>
        <a href="?status=ditolak" class="btn btn-kecil <?= $filter=='ditolak' ? 'btn-biru' : 'btn-abu' ?>">Ditolak</a>
    </div>

    <?php if (mysqli_num_rows($sewaan) == 0): ?>
        <div class="kotak text-center" style="padding:50px">
            <div style="font-size:50px">📋</div>
            <p class="teks-abu mt-10">Belum ada riwayat penyewaan</p>
            <a href="daftar_mobil.php" class="btn btn-biru mt-20">Sewa Mobil Sekarang</a>
        </div>
    <?php else: ?>

    <div class="kotak" style="padding:0">
        <div class="tabel-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Mobil</th>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($s = mysqli_fetch_assoc($sewaan)): ?>
                    <tr>
                        <td><span class="teks-kecil bold"><?= $s['kode_sewa'] ?></span></td>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px">
                                <span style="font-size:24px"><?= $s['foto'] ? '' : '🚗' ?></span>
                                <span class="bold"><?= $s['nama_mobil'] ?></span>
                            </div>
                        </td>
                        <td>
                            <div class="teks-kecil"><?= date('d M Y', strtotime($s['tanggal_mulai'])) ?></div>
                            <div class="teks-kecil teks-abu">s/d <?= date('d M Y', strtotime($s['tanggal_selesai'])) ?></div>
                        </td>
                        <td><?= $s['total_hari'] ?> hari</td>
                        <td><span class="bold">Rp <?= number_format($s['total_harga'], 0, ',', '.') ?></span></td>
                        <td><span class="status status-<?= $s['status'] ?>"><?= ucfirst($s['status']) ?></span></td>
                        <td>
                            <a href="detail_sewa.php?id=<?= $s['id'] ?>" class="btn btn-kecil btn-biru">Detail</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php endif; ?>
</div>
</body>
</html>
