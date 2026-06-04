<?php
$judul = "Semua Penyewaan";
include 'navbar_admin.php';
include 'koneksi.php';

$filter = isset($_GET['status']) ? $_GET['status'] : '';
$where  = "";
if ($filter) $where = "WHERE p.status = '$filter'";

$sewaan = mysqli_query($koneksi, "SELECT p.*, u.nama as nama_user, u.no_hp, m.nama as nama_mobil FROM penyewaan p JOIN users u ON p.user_id = u.id JOIN mobil m ON p.mobil_id = m.id $where ORDER BY p.created_at DESC");
$jumlah = mysqli_num_rows($sewaan);
?>

<div class="page-title">Semua Penyewaan</div>
<div class="page-sub"><?= $jumlah ?> data ditemukan</div>

<!-- filter tab -->
<div class="flex gap-10 mb-20" style="flex-wrap:wrap">
    <a href="admin_sewa.php" class="btn btn-kecil <?= !$filter ? 'btn-biru' : 'btn-abu' ?>">Semua</a>
    <a href="?status=pending" class="btn btn-kecil <?= $filter=='pending' ? 'btn-biru' : 'btn-abu' ?>">⏳ Pending</a>
    <a href="?status=disetujui" class="btn btn-kecil <?= $filter=='disetujui' ? 'btn-biru' : 'btn-abu' ?>">✅ Disetujui</a>
    <a href="?status=aktif" class="btn btn-kecil <?= $filter=='aktif' ? 'btn-biru' : 'btn-abu' ?>">🚗 Aktif</a>
    <a href="?status=selesai" class="btn btn-kecil <?= $filter=='selesai' ? 'btn-biru' : 'btn-abu' ?>">🏁 Selesai</a>
    <a href="?status=ditolak" class="btn btn-kecil <?= $filter=='ditolak' ? 'btn-biru' : 'btn-abu' ?>">❌ Ditolak</a>
</div>

<div class="kotak" style="padding:0">
    <div class="tabel-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>User</th>
                    <th>Mobil</th>
                    <th>Tanggal</th>
                    <th>Total Hari</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($jumlah == 0): ?>
                <tr><td colspan="8" class="text-center" style="padding:40px; color:var(--abu-tua)">Tidak ada data</td></tr>
            <?php endif; ?>
            <?php while($s = mysqli_fetch_assoc($sewaan)): ?>
                <tr>
                    <td class="teks-kecil bold"><?= $s['kode_sewa'] ?></td>
                    <td>
                        <div class="bold"><?= $s['nama_user'] ?></div>
                        <div class="teks-kecil teks-abu"><?= $s['no_hp'] ?></div>
                    </td>
                    <td><?= $s['nama_mobil'] ?></td>
                    <td>
                        <div class="teks-kecil"><?= date('d M Y', strtotime($s['tanggal_mulai'])) ?></div>
                        <div class="teks-kecil teks-abu">s/d <?= date('d M Y', strtotime($s['tanggal_selesai'])) ?></div>
                    </td>
                    <td><?= $s['total_hari'] ?> hari</td>
                    <td>Rp <?= number_format($s['total_harga'], 0, ',', '.') ?></td>
                    <td><span class="status status-<?= $s['status'] ?>"><?= ucfirst($s['status']) ?></span></td>
                    <td><a href="admin_detail_sewa.php?id=<?= $s['id'] ?>" class="btn btn-kecil btn-biru">Detail</a></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
</div>
</body>
</html>
