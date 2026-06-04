<?php
$judul = "Detail Penyewaan";
include 'navbar_admin.php';
include 'koneksi.php';

$id = $_GET['id'];
$cari = mysqli_query($koneksi, "SELECT p.*, u.nama as nama_user, u.email, u.no_hp, u.no_ktp, u.alamat, m.nama as nama_mobil, m.foto, m.plat_nomor, m.harga_per_hari FROM penyewaan p JOIN users u ON p.user_id = u.id JOIN mobil m ON p.mobil_id = m.id WHERE p.id = '$id'");
$sewa = mysqli_fetch_assoc($cari);

if (!$sewa) {
    echo "<div class='konten-admin'><div class='alert alert-error'>Data tidak ditemukan</div></div></div></body></html>";
    exit;
}

// ambil data pembayaran kalau ada
$bayar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM pembayaran WHERE penyewaan_id = '$id'"));

$pesan_sukses = "";
$pesan_error  = "";

// proses aksi
if ($_POST) {
    $aksi = $_POST['aksi'];

    if ($aksi == 'setujui') {
        mysqli_query($koneksi, "UPDATE penyewaan SET status='disetujui' WHERE id='$id'");
        $pesan_sukses = "Penyewaan berhasil disetujui!";
    }

    if ($aksi == 'tolak') {
        $alasan = $_POST['alasan'];
        mysqli_query($koneksi, "UPDATE penyewaan SET status='ditolak', alasan_tolak='$alasan' WHERE id='$id'");
        $pesan_sukses = "Penyewaan ditolak.";
    }

    if ($aksi == 'aktifkan') {
        mysqli_query($koneksi, "UPDATE penyewaan SET status='aktif' WHERE id='$id'");
        mysqli_query($koneksi, "UPDATE mobil SET status='disewa' WHERE id='{$sewa['mobil_id']}'");
        $pesan_sukses = "Status penyewaan diubah ke Aktif!";
    }

    if ($aksi == 'selesaikan') {
        mysqli_query($koneksi, "UPDATE penyewaan SET status='selesai' WHERE id='$id'");
        mysqli_query($koneksi, "UPDATE mobil SET status='tersedia' WHERE id='{$sewa['mobil_id']}'");
        $pesan_sukses = "Penyewaan selesai! Mobil kembali tersedia.";
    }

    // refresh data
    $cari = mysqli_query($koneksi, "SELECT p.*, u.nama as nama_user, u.email, u.no_hp, u.no_ktp, u.alamat, m.nama as nama_mobil, m.foto, m.plat_nomor, m.harga_per_hari FROM penyewaan p JOIN users u ON p.user_id = u.id JOIN mobil m ON p.mobil_id = m.id WHERE p.id = '$id'");
    $sewa = mysqli_fetch_assoc($cari);
}
?>

<p class="mb-10"><a href="admin_sewa.php">← Kembali</a></p>
<div class="page-title">Detail Penyewaan</div>
<div class="page-sub">Kode: <strong><?= $sewa['kode_sewa'] ?></strong></div>

<?php if ($pesan_sukses): ?>
    <div class="alert alert-sukses"><?= $pesan_sukses ?></div>
<?php endif; ?>
<?php if ($pesan_error): ?>
    <div class="alert alert-error"><?= $pesan_error ?></div>
<?php endif; ?>

<div class="form-row">
    <!-- Kiri: info sewa + mobil -->
    <div>
        <div class="kotak">
            <div class="kotak-judul">Info Penyewa</div>
            <div class="info-baris">
                <span class="label">Nama</span>
                <span class="nilai"><?= $sewa['nama_user'] ?></span>
            </div>
            <div class="info-baris">
                <span class="label">Email</span>
                <span class="nilai"><?= $sewa['email'] ?></span>
            </div>
            <div class="info-baris">
                <span class="label">No HP</span>
                <span class="nilai"><?= $sewa['no_hp'] ?: '-' ?></span>
            </div>
            <div class="info-baris">
                <span class="label">No KTP</span>
                <span class="nilai"><?= $sewa['no_ktp'] ?: '<span class="teks-kecil" style="color:var(--merah)">Belum diisi</span>' ?></span>
            </div>
            <div class="info-baris">
                <span class="label">Alamat</span>
                <span class="nilai"><?= $sewa['alamat'] ?: '-' ?></span>
            </div>
        </div>

        <div class="kotak">
            <div class="kotak-judul">Info Mobil</div>
            <div class="info-baris">
                <span class="label">Nama Mobil</span>
                <span class="nilai"><?= $sewa['nama_mobil'] ?></span>
            </div>
            <div class="info-baris">
                <span class="label">Plat Nomor</span>
                <span class="nilai"><?= $sewa['plat_nomor'] ?></span>
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
            <?php if ($sewa['catatan']): ?>
            <div class="info-baris">
                <span class="label">Catatan</span>
                <span class="nilai"><?= $sewa['catatan'] ?></span>
            </div>
            <?php endif; ?>
            <div class="info-baris">
                <span class="label">Status Sewa</span>
                <span class="nilai"><span class="status status-<?= $sewa['status'] ?>"><?= ucfirst($sewa['status']) ?></span></span>
            </div>
            <?php if ($sewa['alasan_tolak']): ?>
            <div class="alert alert-error mt-10">Alasan ditolak: <?= $sewa['alasan_tolak'] ?></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Kanan: aksi + pembayaran -->
    <div>
        <!-- Tombol Aksi -->
        <div class="kotak">
            <div class="kotak-judul">Tindakan Admin</div>

            <?php if ($sewa['status'] == 'pending'): ?>
            <p class="teks-kecil teks-abu mb-20">Pengajuan ini menunggu keputusan kamu.</p>
            <form method="POST" style="margin-bottom:12px">
                <input type="hidden" name="aksi" value="setujui">
                <button type="submit" class="btn btn-hijau btn-full">✅ Setujui Penyewaan</button>
            </form>
            <form method="POST">
                <input type="hidden" name="aksi" value="tolak">
                <div class="form-group">
                    <label>Alasan Penolakan</label>
                    <textarea name="alasan" placeholder="Contoh: KTP tidak lengkap, tanggal bentrok..." required></textarea>
                </div>
                <button type="submit" class="btn btn-merah btn-full">❌ Tolak Penyewaan</button>
            </form>

            <?php elseif ($sewa['status'] == 'disetujui'): ?>
            <p class="teks-kecil teks-abu mb-20">Penyewaan sudah disetujui. Aktifkan ketika mobil sudah diambil penyewa.</p>
            <form method="POST">
                <input type="hidden" name="aksi" value="aktifkan">
                <button type="submit" class="btn btn-biru btn-full">🚗 Aktifkan (Mobil Sudah Diambil)</button>
            </form>

            <?php elseif ($sewa['status'] == 'aktif'): ?>
            <p class="teks-kecil teks-abu mb-20">Mobil sedang aktif disewa. Selesaikan ketika mobil sudah dikembalikan.</p>
            <form method="POST">
                <input type="hidden" name="aksi" value="selesaikan">
                <button type="submit" class="btn btn-hijau btn-full">🏁 Selesaikan (Mobil Sudah Kembali)</button>
            </form>

            <?php else: ?>
            <p class="teks-abu">Tidak ada aksi yang tersedia untuk status ini.</p>
            <?php endif; ?>
        </div>

        <!-- Pembayaran -->
        <div class="kotak">
            <div class="kotak-judul">Info Pembayaran</div>
            <?php if ($bayar): ?>
                <div class="info-baris">
                    <span class="label">Metode</span>
                    <span class="nilai"><?= $bayar['metode'] ?></span>
                </div>
                <div class="info-baris">
                    <span class="label">Jumlah</span>
                    <span class="nilai">Rp <?= number_format($bayar['jumlah'], 0, ',', '.') ?></span>
                </div>
                <div class="info-baris">
                    <span class="label">Status</span>
                    <span class="nilai"><span class="status status-<?= $bayar['status'] ?>"><?= ucfirst($bayar['status']) ?></span></span>
                </div>
                <?php if ($bayar['bukti_bayar']): ?>
                <div class="mt-10">
                    <p class="teks-kecil teks-abu mb-10">Bukti Pembayaran:</p>
                    <img src="uploads/<?= $bayar['bukti_bayar'] ?>" style="max-width:100%; border-radius:8px; border:1px solid var(--border)">
                </div>
                <?php endif; ?>
                <p class="mt-10"><a href="admin_pembayaran.php?id=<?= $bayar['id'] ?>" class="btn btn-kecil btn-biru">Kelola Pembayaran</a></p>
            <?php else: ?>
                <p class="teks-abu teks-kecil">Belum ada pembayaran yang dikirim.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

    </div>
</div>
</body>
</html>
