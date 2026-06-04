<?php
$judul = "Data Mobil";
include 'navbar_admin.php';
include 'koneksi.php';

$pesan_sukses = "";
$pesan_error  = "";

// HAPUS MOBIL
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    // cek apakah masih ada sewa aktif
    $cek = mysqli_query($koneksi, "SELECT id FROM penyewaan WHERE mobil_id = '$id_hapus' AND status IN ('pending','disetujui','aktif')");
    if (mysqli_num_rows($cek) > 0) {
        $pesan_error = "Tidak bisa hapus, mobil masih ada penyewaan aktif!";
    } else {
        mysqli_query($koneksi, "DELETE FROM mobil WHERE id = '$id_hapus'");
        $pesan_sukses = "Mobil berhasil dihapus.";
    }
}

// TAMBAH MOBIL
if ($_POST && isset($_POST['aksi']) && $_POST['aksi'] == 'tambah') {
    $nama        = $_POST['nama'];
    $merek       = $_POST['merek'];
    $tahun       = $_POST['tahun'];
    $warna       = $_POST['warna'];
    $transmisi   = $_POST['transmisi'];
    $kapasitas   = $_POST['kapasitas'];
    $harga       = $_POST['harga_per_hari'];
    $deskripsi   = $_POST['deskripsi'];
    $plat        = $_POST['plat_nomor'];
    $foto        = "";

    if ($_FILES['foto']['name']) {
        $ext  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto = 'mobil_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
    }

    mysqli_query($koneksi, "INSERT INTO mobil (nama, merek, tahun, warna, transmisi, kapasitas, harga_per_hari, deskripsi, foto, plat_nomor) VALUES ('$nama','$merek','$tahun','$warna','$transmisi','$kapasitas','$harga','$deskripsi','$foto','$plat')");
    $pesan_sukses = "Mobil berhasil ditambahkan!";
}

// EDIT MOBIL
if ($_POST && isset($_POST['aksi']) && $_POST['aksi'] == 'edit') {
    $id_edit   = $_POST['id'];
    $nama      = $_POST['nama'];
    $merek     = $_POST['merek'];
    $tahun     = $_POST['tahun'];
    $warna     = $_POST['warna'];
    $transmisi = $_POST['transmisi'];
    $kapasitas = $_POST['kapasitas'];
    $harga     = $_POST['harga_per_hari'];
    $deskripsi = $_POST['deskripsi'];
    $plat      = $_POST['plat_nomor'];
    $status    = $_POST['status'];

    $update_foto = "";
    if ($_FILES['foto']['name']) {
        $ext         = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto        = 'mobil_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], 'uploads/' . $foto);
        $update_foto = ", foto = '$foto'";
    }

    mysqli_query($koneksi, "UPDATE mobil SET nama='$nama', merek='$merek', tahun='$tahun', warna='$warna', transmisi='$transmisi', kapasitas='$kapasitas', harga_per_hari='$harga', deskripsi='$deskripsi', plat_nomor='$plat', status='$status' $update_foto WHERE id='$id_edit'");
    $pesan_sukses = "Data mobil berhasil diperbarui!";
}

$semua_mobil = mysqli_query($koneksi, "SELECT * FROM mobil ORDER BY id DESC");

// kalau ada ?edit=id, ambil data untuk form edit
$data_edit = null;
if (isset($_GET['edit'])) {
    $id_edit   = $_GET['edit'];
    $cari_edit = mysqli_query($koneksi, "SELECT * FROM mobil WHERE id = '$id_edit'");
    $data_edit = mysqli_fetch_assoc($cari_edit);
}
?>

<div class="flex justify-between items-center mb-20">
    <div>
        <div class="page-title">Data Mobil</div>
        <div class="page-sub">Kelola semua kendaraan yang tersedia</div>
    </div>
    <button onclick="document.getElementById('form-tambah').style.display = document.getElementById('form-tambah').style.display == 'none' ? 'block' : 'none'" class="btn btn-biru">
        + Tambah Mobil
    </button>
</div>

<?php if ($pesan_sukses): ?>
    <div class="alert alert-sukses"><?= $pesan_sukses ?></div>
<?php endif; ?>
<?php if ($pesan_error): ?>
    <div class="alert alert-error"><?= $pesan_error ?></div>
<?php endif; ?>

<!-- Form Tambah -->
<div id="form-tambah" style="display:<?= ($data_edit ? 'none' : 'none') ?>">
    <div class="kotak">
        <div class="kotak-judul">Tambah Mobil Baru</div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="aksi" value="tambah">
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Mobil</label>
                    <input type="text" name="nama" placeholder="Toyota Avanza 2022" required>
                </div>
                <div class="form-group">
                    <label>Merek</label>
                    <input type="text" name="merek" placeholder="Toyota" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Tahun</label>
                    <input type="number" name="tahun" placeholder="2022" required>
                </div>
                <div class="form-group">
                    <label>Warna</label>
                    <input type="text" name="warna" placeholder="Putih">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Transmisi</label>
                    <select name="transmisi">
                        <option value="Manual">Manual</option>
                        <option value="Automatic">Automatic</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kapasitas (kursi)</label>
                    <input type="number" name="kapasitas" placeholder="5" value="5">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Harga per Hari (Rp)</label>
                    <input type="number" name="harga_per_hari" placeholder="350000" required>
                </div>
                <div class="form-group">
                    <label>Plat Nomor</label>
                    <input type="text" name="plat_nomor" placeholder="BK 1234 AA">
                </div>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" placeholder="Deskripsi singkat mobil..."></textarea>
            </div>
            <div class="form-group">
                <label>Foto Mobil</label>
                <input type="file" name="foto" accept="image/*">
            </div>
            <div class="flex gap-10">
                <button type="submit" class="btn btn-hijau">Simpan Mobil</button>
                <button type="button" onclick="document.getElementById('form-tambah').style.display='none'" class="btn btn-abu">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Form Edit -->
<?php if ($data_edit): ?>
<div class="kotak" style="border:2px solid var(--kuning)">
    <div class="kotak-judul">Edit Mobil: <?= $data_edit['nama'] ?></div>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="aksi" value="edit">
        <input type="hidden" name="id" value="<?= $data_edit['id'] ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Nama Mobil</label>
                <input type="text" name="nama" value="<?= $data_edit['nama'] ?>" required>
            </div>
            <div class="form-group">
                <label>Merek</label>
                <input type="text" name="merek" value="<?= $data_edit['merek'] ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Tahun</label>
                <input type="number" name="tahun" value="<?= $data_edit['tahun'] ?>">
            </div>
            <div class="form-group">
                <label>Warna</label>
                <input type="text" name="warna" value="<?= $data_edit['warna'] ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Transmisi</label>
                <select name="transmisi">
                    <option value="Manual" <?= $data_edit['transmisi']=='Manual'?'selected':'' ?>>Manual</option>
                    <option value="Automatic" <?= $data_edit['transmisi']=='Automatic'?'selected':'' ?>>Automatic</option>
                </select>
            </div>
            <div class="form-group">
                <label>Kapasitas</label>
                <input type="number" name="kapasitas" value="<?= $data_edit['kapasitas'] ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Harga per Hari</label>
                <input type="number" name="harga_per_hari" value="<?= $data_edit['harga_per_hari'] ?>" required>
            </div>
            <div class="form-group">
                <label>Plat Nomor</label>
                <input type="text" name="plat_nomor" value="<?= $data_edit['plat_nomor'] ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="tersedia" <?= $data_edit['status']=='tersedia'?'selected':'' ?>>Tersedia</option>
                    <option value="disewa" <?= $data_edit['status']=='disewa'?'selected':'' ?>>Disewa</option>
                    <option value="maintenance" <?= $data_edit['status']=='maintenance'?'selected':'' ?>>Maintenance</option>
                </select>
            </div>
            <div class="form-group">
                <label>Foto Baru (opsional)</label>
                <input type="file" name="foto" accept="image/*">
            </div>
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi"><?= $data_edit['deskripsi'] ?></textarea>
        </div>
        <div class="flex gap-10">
            <button type="submit" class="btn btn-kuning">Simpan Perubahan</button>
            <a href="admin_mobil.php" class="btn btn-abu">Batal</a>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Tabel Mobil -->
<div class="kotak" style="padding:0">
    <div style="padding:16px 20px; border-bottom:1px solid var(--border)" class="kotak-judul" style="margin:0; padding-bottom:16px">Daftar Semua Mobil</div>
    <div class="tabel-wrap">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Tahun</th>
                    <th>Transmisi</th>
                    <th>Plat</th>
                    <th>Harga/Hari</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php while($m = mysqli_fetch_assoc($semua_mobil)): ?>
                <tr>
                    <td>
                        <div style="width:48px; height:36px; background:linear-gradient(135deg,#e0e7ff,#bfdbfe); border-radius:6px; display:flex; align-items:center; justify-content:center; font-size:20px">
                            <?= $m['foto'] ? '<img src="uploads/'.$m['foto'].'" style="width:48px;height:36px;object-fit:cover;border-radius:6px">' : '🚗' ?>
                        </div>
                    </td>
                    <td>
                        <div class="bold"><?= $m['nama'] ?></div>
                        <div class="teks-kecil teks-abu"><?= $m['merek'] ?></div>
                    </td>
                    <td><?= $m['tahun'] ?></td>
                    <td><?= $m['transmisi'] ?></td>
                    <td class="teks-kecil"><?= $m['plat_nomor'] ?></td>
                    <td>Rp <?= number_format($m['harga_per_hari'], 0, ',', '.') ?></td>
                    <td><span class="status status-<?= $m['status'] ?>"><?= ucfirst($m['status']) ?></span></td>
                    <td>
                        <div class="flex gap-10">
                            <a href="admin_mobil.php?edit=<?= $m['id'] ?>" class="btn btn-kecil btn-kuning">Edit</a>
                            <a href="admin_mobil.php?hapus=<?= $m['id'] ?>" class="btn btn-kecil btn-merah" onclick="return confirm('Yakin hapus mobil ini?')">Hapus</a>
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
