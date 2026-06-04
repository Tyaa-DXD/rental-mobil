<?php
$judul = "Detail Penyewaan";
include 'navbar_user.php';
include 'koneksi.php';

$id      = $_GET['id'];
$user_id = $_SESSION['user_id'];

// ambil data sewa, pastikan punya user ini
$cari  = mysqli_query($koneksi, "SELECT p.*, m.nama as nama_mobil, m.foto, m.harga_per_hari, m.plat_nomor FROM penyewaan p JOIN mobil m ON p.mobil_id = m.id WHERE p.id = '$id' AND p.user_id = '$user_id'");
$sewa  = mysqli_fetch_assoc($cari);

if (!$sewa) {
    echo "<div class='container'><div class='alert alert-error'>Data tidak ditemukan!</div></div></body></html>";
    exit;
}

// cek apakah sudah ada pembayaran
$cek_bayar = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE penyewaan_id = '$id'");
$bayar     = mysqli_fetch_assoc($cek_bayar);

$pesan_sukses = "";
$pesan_error  = "";

// proses upload bukti bayar
if ($_POST && !$bayar) {
    $metode = $_POST['metode'];
    $jumlah = $sewa['total_harga'];

    $nama_file = "";
    if ($_FILES['bukti_bayar']['name']) {
        $ext       = pathinfo($_FILES['bukti_bayar']['name'], PATHINFO_EXTENSION);
        $nama_file = 'bukti_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['bukti_bayar']['tmp_name'], 'uploads/' . $nama_file);
    }

    $kode_bayar = 'BY' . date('ymd') . rand(100,999);
    mysqli_query($koneksi, "INSERT INTO pembayaran (penyewaan_id, jumlah, metode, bukti_bayar, kode_bayar) VALUES ('$id', '$jumlah', '$metode', '$nama_file', '$kode_bayar')");

    $pesan_sukses = "Pembayaran berhasil dikirim! Menunggu konfirmasi admin.";

    // refresh data
    $cek_bayar = mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE penyewaan_id = '$id'");
    $bayar     = mysqli_fetch_assoc($cek_bayar);
}
?>

<div class="container">
    <?php if (isset($_GET['baru'])): ?>
        <div class="alert alert-sukses">Pengajuan sewa berhasil! Silahkan lakukan pembayaran.</div>
    <?php endif; ?>

    <?php if ($pesan_sukses): ?>
        <div class="alert alert-sukses"><?= $pesan_sukses ?></div>
    <?php endif; ?>
    <?php if ($pesan_error): ?>
        <div class="alert alert-error"><?= $pesan_error ?></div>
    <?php endif; ?>

    <p class="mb-10"><a href="riwayat_sewa.php">← Kembali ke Riwayat</a></p>
    <div class="page-title">Detail Penyewaan</div>
    <div class="page-sub">Kode: <strong><?= $sewa['kode_sewa'] ?></strong></div>

    <div class="form-row">
        <!-- Info Sewa -->
        <div>
            <div class="kotak">
                <div class="kotak-judul">Informasi Mobil</div>
                <div style="display:flex; gap:14px; align-items:center; margin-bottom:16px">
                    <div style="width:70px; height:70px; background:linear-gradient(135deg,#e0e7ff,#bfdbfe); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:32px; flex-shrink:0">
                        <?php if ($sewa['foto']): ?>
                            <img src="uploads/<?= $sewa['foto'] ?>" style="width:100%;height:100%;object-fit:cover;border-radius:8px">
                        <?php else: ?>
                            🚗
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="bold"><?= $sewa['nama_mobil'] ?></div>
                        <div class="teks-kecil teks-abu"><?= $sewa['plat_nomor'] ?></div>
                    </div>
                </div>

                <div class="info-baris">
                    <span class="label">Tanggal Mulai</span>
                    <span class="nilai"><?= date('d M Y', strtotime($sewa['tanggal_mulai'])) ?></span>
                </div>
                <div class="info-baris">
                    <span class="label">Tanggal Selesai</span>
                    <span class="nilai"><?= date('d M Y', strtotime($sewa['tanggal_selesai'])) ?></span>
                </div>
                <div class="info-baris">
                    <span class="label">Total Hari</span>
                    <span class="nilai"><?= $sewa['total_hari'] ?> hari</span>
                </div>
                <div class="info-baris">
                    <span class="label">Total Harga</span>
                    <span class="nilai" style="color:var(--biru-muda); font-size:17px">Rp <?= number_format($sewa['total_harga'], 0, ',', '.') ?></span>
                </div>
                <div class="info-baris">
                    <span class="label">Status Sewa</span>
                    <span class="nilai"><span class="status status-<?= $sewa['status'] ?>"><?= ucfirst($sewa['status']) ?></span></span>
                </div>
                <?php if ($sewa['catatan']): ?>
                <div class="info-baris">
                    <span class="label">Catatan</span>
                    <span class="nilai"><?= $sewa['catatan'] ?></span>
                </div>
                <?php endif; ?>
                <?php if ($sewa['alasan_tolak']): ?>
                <div class="alert alert-error mt-10">
                    <strong>Alasan ditolak:</strong> <?= $sewa['alasan_tolak'] ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pembayaran -->
        <div>
            <div class="kotak">
                <div class="kotak-judul">Pembayaran</div>

                <?php if ($bayar): ?>
                    <!-- sudah bayar, tampilkan status -->
                    <div class="info-baris">
                        <span class="label">Kode Bayar</span>
                        <span class="nilai"><?= $bayar['kode_bayar'] ?></span>
                    </div>
                    <div class="info-baris">
                        <span class="label">Metode</span>
                        <span class="nilai"><?= $bayar['metode'] ?></span>
                    </div>
                    <div class="info-baris">
                        <span class="label">Jumlah</span>
                        <span class="nilai">Rp <?= number_format($bayar['jumlah'], 0, ',', '.') ?></span>
                    </div>
                    <div class="info-baris">
                        <span class="label">Status Bayar</span>
                        <span class="nilai"><span class="status status-<?= $bayar['status'] ?>"><?= ucfirst($bayar['status']) ?></span></span>
                    </div>
                    <?php if ($bayar['bukti_bayar']): ?>
                    <div class="mt-10">
                        <p class="teks-kecil teks-abu mb-10">Bukti Pembayaran:</p>
                        <img src="uploads/<?= $bayar['bukti_bayar'] ?>" style="max-width:100%; border-radius:8px; border:1px solid var(--border)">
                    </div>
                    <?php endif; ?>

                <?php elseif ($sewa['status'] == 'pending' || $sewa['status'] == 'disetujui'): ?>
                    <!-- form bayar -->
                    <div class="alert alert-info mb-20">
                        Silahkan lakukan pembayaran sebesar <strong>Rp <?= number_format($sewa['total_harga'], 0, ',', '.') ?></strong>
                    </div>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Metode Pembayaran</label>
                            <select name="metode" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Kartu Kredit">Kartu Kredit</option>
                                <option value="Dompet Digital">Dompet Digital (GoPay/OVO)</option>
                                <option value="Tunai">Tunai di Tempat</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Upload Bukti Bayar</label>
                            <input type="file" name="bukti_bayar" accept="image/*">
                            <p class="teks-kecil teks-abu mt-10">Format: JPG, PNG. Opsional untuk tunai.</p>
                        </div>
                        <button type="submit" class="btn btn-hijau btn-full">Kirim Pembayaran</button>
                    </form>

                <?php elseif ($sewa['status'] == 'ditolak' || $sewa['status'] == 'dibatalkan'): ?>
                    <div class="alert alert-error">Penyewaan ini tidak memerlukan pembayaran.</div>
                <?php else: ?>
                    <p class="teks-abu">Tidak ada tagihan pembayaran.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
