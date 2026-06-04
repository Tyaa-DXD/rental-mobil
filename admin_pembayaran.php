<?php
$judul = "Kelola Pembayaran";
include 'navbar_admin.php';
include 'koneksi.php';

$pesan_sukses = "";

// konfirmasi atau tolak pembayaran
if ($_POST) {
    $id_bayar = $_POST['id_bayar'];
    $aksi     = $_POST['aksi'];

    if ($aksi == 'konfirmasi') {
        mysqli_query($koneksi, "UPDATE pembayaran SET status='dikonfirmasi' WHERE id='$id_bayar'");
        $pesan_sukses = "Pembayaran berhasil dikonfirmasi!";
    }

    if ($aksi == 'tolak') {
        mysqli_query($koneksi, "UPDATE pembayaran SET status='ditolak' WHERE id='$id_bayar'");
        $pesan_sukses = "Pembayaran ditolak.";
    }
}

// kalau ada ?id= tampilkan detail satu pembayaran
if (isset($_GET['id'])) {
    $id_bayar    = $_GET['id'];
    $cari        = mysqli_query($koneksi, "SELECT pb.*, p.kode_sewa, p.total_harga, p.status as status_sewa, u.nama as nama_user, u.no_hp, m.nama as nama_mobil FROM pembayaran pb JOIN penyewaan p ON pb.penyewaan_id = p.id JOIN users u ON p.user_id = u.id JOIN mobil m ON p.mobil_id = m.id WHERE pb.id = '$id_bayar'");
    $detail_bayar = mysqli_fetch_assoc($cari);
}

// semua pembayaran
$filter  = isset($_GET['status']) ? $_GET['status'] : '';
$where   = "";
if ($filter) $where = "WHERE pb.status = '$filter'";

$semua = mysqli_query($koneksi, "SELECT pb.*, p.kode_sewa, p.total_harga, u.nama as nama_user, m.nama as nama_mobil FROM pembayaran pb JOIN penyewaan p ON pb.penyewaan_id = p.id JOIN users u ON p.user_id = u.id JOIN mobil m ON p.mobil_id = m.id $where ORDER BY pb.created_at DESC");
?>

<div class="page-title">Kelola Pembayaran</div>
<div class="page-sub">Konfirmasi dan verifikasi pembayaran dari penyewa</div>

<?php if ($pesan_sukses): ?>
    <div class="alert alert-sukses"><?= $pesan_sukses ?></div>
<?php endif; ?>

<!-- Detail satu pembayaran -->
<?php if (isset($detail_bayar)): ?>
<div class="kotak" style="border:2px solid var(--kuning); margin-bottom:24px">
    <div class="kotak-judul">Detail Pembayaran - <?= $detail_bayar['kode_bayar'] ?></div>
    <div class="form-row">
        <div>
            <div class="info-baris">
                <span class="label">Penyewa</span>
                <span class="nilai"><?= $detail_bayar['nama_user'] ?></span>
            </div>
            <div class="info-baris">
                <span class="label">Kode Sewa</span>
                <span class="nilai"><?= $detail_bayar['kode_sewa'] ?></span>
            </div>
            <div class="info-baris">
                <span class="label">Mobil</span>
                <span class="nilai"><?= $detail_bayar['nama_mobil'] ?></span>
            </div>
            <div class="info-baris">
                <span class="label">Jumlah Bayar</span>
                <span class="nilai" style="color:var(--biru-muda); font-size:17px">Rp <?= number_format($detail_bayar['jumlah'], 0, ',', '.') ?></span>
            </div>
            <div class="info-baris">
                <span class="label">Metode</span>
                <span class="nilai"><?= $detail_bayar['metode'] ?></span>
            </div>
            <div class="info-baris">
                <span class="label">Status Bayar</span>
                <span class="nilai"><span class="status status-<?= $detail_bayar['status'] ?>"><?= ucfirst($detail_bayar['status']) ?></span></span>
            </div>

            <?php if ($detail_bayar['status'] == 'menunggu'): ?>
            <div style="margin-top:16px; display:flex; gap:10px">
                <form method="POST" style="display:inline">
                    <input type="hidden" name="id_bayar" value="<?= $detail_bayar['id'] ?>">
                    <input type="hidden" name="aksi" value="konfirmasi">
                    <button type="submit" class="btn btn-hijau">✅ Konfirmasi</button>
                </form>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="id_bayar" value="<?= $detail_bayar['id'] ?>">
                    <input type="hidden" name="aksi" value="tolak">
                    <button type="submit" class="btn btn-merah">❌ Tolak</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <div>
            <?php if ($detail_bayar['bukti_bayar']): ?>
                <p class="teks-kecil teks-abu mb-10">Bukti Pembayaran:</p>
                <img src="uploads/<?= $detail_bayar['bukti_bayar'] ?>" style="max-width:100%; border-radius:8px; border:1px solid var(--border)">
            <?php else: ?>
                <p class="teks-abu">Tidak ada bukti bayar diunggah.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Filter -->
<div class="flex gap-10 mb-20">
    <a href="admin_pembayaran.php" class="btn btn-kecil <?= !$filter ? 'btn-biru' : 'btn-abu' ?>">Semua</a>
    <a href="?status=menunggu" class="btn btn-kecil <?= $filter=='menunggu' ? 'btn-biru' : 'btn-abu' ?>">⏳ Menunggu</a>
    <a href="?status=dikonfirmasi" class="btn btn-kecil <?= $filter=='dikonfirmasi' ? 'btn-biru' : 'btn-abu' ?>">✅ Dikonfirmasi</a>
    <a href="?status=ditolak" class="btn btn-kecil <?= $filter=='ditolak' ? 'btn-biru' : 'btn-abu' ?>">❌ Ditolak</a>
</div>

<div class="kotak" style="padding:0">
    <div class="tabel-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode Bayar</th>
                    <th>Penyewa</th>
                    <th>Mobil</th>
                    <th>Jumlah</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($semua) == 0): ?>
                <tr><td colspan="8" class="text-center" style="padding:40px; color:var(--abu-tua)">Tidak ada data</td></tr>
            <?php endif; ?>
            <?php while($b = mysqli_fetch_assoc($semua)): ?>
                <tr>
                    <td class="teks-kecil bold"><?= $b['kode_bayar'] ?></td>
                    <td><?= $b['nama_user'] ?></td>
                    <td><?= $b['nama_mobil'] ?></td>
                    <td>Rp <?= number_format($b['jumlah'], 0, ',', '.') ?></td>
                    <td class="teks-kecil"><?= $b['metode'] ?></td>
                    <td><span class="status status-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                    <td class="teks-kecil"><?= date('d M Y', strtotime($b['created_at'])) ?></td>
                    <td>
                        <div class="flex gap-10">
                            <a href="admin_pembayaran.php?id=<?= $b['id'] ?>" class="btn btn-kecil btn-biru">Detail</a>
                            <?php if ($b['status'] == 'menunggu'): ?>
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="id_bayar" value="<?= $b['id'] ?>">
                                <input type="hidden" name="aksi" value="konfirmasi">
                                <button type="submit" class="btn btn-kecil btn-hijau">✅</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
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
