<?php
$judul = "Detail Mobil";
include 'navbar_user.php';
include 'koneksi.php';

$id = $_GET['id'];
$cari = mysqli_query($koneksi, "SELECT * FROM mobil WHERE id = '$id'");
$mobil = mysqli_fetch_assoc($cari);

if (!$mobil) {
    echo "<div class='container'><div class='alert alert-error'>Mobil tidak ditemukan!</div></div></body></html>";
    exit;
}

$pesan_sukses = "";
$pesan_error  = "";

if ($_POST) {
    $tgl_mulai  = $_POST['tanggal_mulai'];
    $tgl_selesai = $_POST['tanggal_selesai'];
    $catatan    = $_POST['catatan'];

    // hitung total hari
    $selisih   = strtotime($tgl_selesai) - strtotime($tgl_mulai);
    $total_hari = ceil($selisih / (60 * 60 * 24));

    if ($total_hari <= 0) {
        $pesan_error = "Tanggal selesai harus lebih dari tanggal mulai!";
    } elseif ($mobil['status'] != 'tersedia') {
        $pesan_error = "Maaf, mobil ini sedang tidak tersedia!";
    } else {
        $total_harga = $total_hari * $mobil['harga_per_hari'];

        // buat kode unik
        $kode = 'SW' . date('ymd') . rand(100,999);
        $user_id = $_SESSION['user_id'];

        mysqli_query($koneksi, "INSERT INTO penyewaan (kode_sewa, user_id, mobil_id, tanggal_mulai, tanggal_selesai, total_hari, total_harga, catatan) VALUES ('$kode', '$user_id', '$id', '$tgl_mulai', '$tgl_selesai', '$total_hari', '$total_harga', '$catatan')");

        $id_sewa = mysqli_insert_id($koneksi);
        header("Location: detail_sewa.php?id=$id_sewa&baru=1");
        exit;
    }
}
?>

<div class="container">
    <p class="mb-10"><a href="daftar_mobil.php">← Kembali ke Daftar Mobil</a></p>

    <?php if ($pesan_error): ?>
        <div class="alert alert-error"><?= $pesan_error ?></div>
    <?php endif; ?>

    <div class="detail-wrap">
        <!-- Foto & info -->
        <div>
            <div class="detail-foto">
                <?php if ($mobil['foto']): ?>
                    <img src="uploads/<?= $mobil['foto'] ?>" alt="<?= $mobil['nama'] ?>">
                <?php else: ?>
                    🚗
                <?php endif; ?>
            </div>

            <div class="kotak mt-20">
                <div class="kotak-judul"><?= $mobil['nama'] ?></div>
                <div class="info-baris">
                    <span class="label">Merek</span>
                    <span class="nilai"><?= $mobil['merek'] ?></span>
                </div>
                <div class="info-baris">
                    <span class="label">Tahun</span>
                    <span class="nilai"><?= $mobil['tahun'] ?></span>
                </div>
                <div class="info-baris">
                    <span class="label">Warna</span>
                    <span class="nilai"><?= $mobil['warna'] ?></span>
                </div>
                <div class="info-baris">
                    <span class="label">Transmisi</span>
                    <span class="nilai"><?= $mobil['transmisi'] ?></span>
                </div>
                <div class="info-baris">
                    <span class="label">Kapasitas</span>
                    <span class="nilai"><?= $mobil['kapasitas'] ?> kursi</span>
                </div>
                <div class="info-baris">
                    <span class="label">Plat Nomor</span>
                    <span class="nilai"><?= $mobil['plat_nomor'] ?></span>
                </div>
                <div class="info-baris">
                    <span class="label">Status</span>
                    <span class="nilai"><span class="status status-<?= $mobil['status'] ?>"><?= ucfirst($mobil['status']) ?></span></span>
                </div>
                <div class="info-baris">
                    <span class="label">Harga / Hari</span>
                    <span class="nilai" style="color:var(--biru-muda); font-size:18px">Rp <?= number_format($mobil['harga_per_hari'], 0, ',', '.') ?></span>
                </div>

                <?php if ($mobil['deskripsi']): ?>
                <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--border)">
                    <p class="teks-kecil teks-abu"><?= $mobil['deskripsi'] ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Form sewa -->
        <div>
            <div class="kotak">
                <div class="kotak-judul">Form Penyewaan</div>

                <?php if ($mobil['status'] != 'tersedia'): ?>
                    <div class="alert alert-error">Mobil ini sedang tidak tersedia untuk disewa.</div>
                <?php else: ?>

                <form method="POST" id="formSewa">
                    <div class="form-group">
                        <label>Tanggal Mulai Sewa</label>
                        <input type="date" name="tanggal_mulai" id="tgl_mulai" min="<?= date('Y-m-d') ?>" required onchange="hitungHarga()">
                    </div>
                    <div class="form-group">
                        <label>Tanggal Selesai Sewa</label>
                        <input type="date" name="tanggal_selesai" id="tgl_selesai" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required onchange="hitungHarga()">
                    </div>

                    <!-- preview harga -->
                    <div id="preview-harga" style="display:none; background:var(--abu); border-radius:8px; padding:14px; margin-bottom:16px">
                        <div class="flex justify-between">
                            <span class="teks-kecil teks-abu">Total Hari</span>
                            <span class="bold" id="total-hari">-</span>
                        </div>
                        <div class="flex justify-between mt-10">
                            <span class="teks-kecil teks-abu">Harga per Hari</span>
                            <span>Rp <?= number_format($mobil['harga_per_hari'], 0, ',', '.') ?></span>
                        </div>
                        <hr style="margin:10px 0; border:none; border-top:1px solid var(--border)">
                        <div class="flex justify-between">
                            <span class="bold">Total Harga</span>
                            <span class="bold" style="color:var(--biru-muda); font-size:18px" id="total-harga">-</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Catatan (opsional)</label>
                        <textarea name="catatan" placeholder="Contoh: jemput di bandara, perlu kursi bayi, dll..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-biru btn-full" style="padding:13px">Ajukan Penyewaan</button>
                    <p class="teks-kecil teks-abu mt-10 text-center">Penyewaan akan dikonfirmasi oleh admin</p>
                </form>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function hitungHarga() {
    var mulai   = document.getElementById('tgl_mulai').value;
    var selesai = document.getElementById('tgl_selesai').value;

    if (mulai && selesai) {
        var selisih = new Date(selesai) - new Date(mulai);
        var hari    = Math.ceil(selisih / (1000 * 60 * 60 * 24));

        if (hari > 0) {
            var hargaPerHari = <?= $mobil['harga_per_hari'] ?>;
            var total        = hari * hargaPerHari;

            document.getElementById('total-hari').textContent  = hari + ' hari';
            document.getElementById('total-harga').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('preview-harga').style.display = 'block';
        } else {
            document.getElementById('preview-harga').style.display = 'none';
        }
    }
}
</script>

</body>
</html>
