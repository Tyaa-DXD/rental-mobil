<?php
$judul = "Profil Saya";
include 'navbar_user.php';
include 'koneksi.php';

$user_id = $_SESSION['user_id'];
$cari    = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'");
$user    = mysqli_fetch_assoc($cari);

$pesan_sukses = "";
$pesan_error  = "";

if ($_POST) {
    $nama   = $_POST['nama'];
    $no_hp  = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];

    // update password kalau diisi
    $update_pass = "";
    if ($_POST['password_baru']) {
        if (!password_verify($_POST['password_lama'], $user['password'])) {
            $pesan_error = "Password lama salah!";
        } else {
            $hash        = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
            $update_pass = ", password = '$hash'";
        }
    }

    if (!$pesan_error) {
        mysqli_query($koneksi, "UPDATE users SET nama='$nama', no_hp='$no_hp', alamat='$alamat', no_ktp='$no_ktp' $update_pass WHERE id='$user_id'");
        $_SESSION['nama'] = $nama;
        $pesan_sukses     = "Profil berhasil diperbarui!";

        // refresh data
        $cari = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'");
        $user = mysqli_fetch_assoc($cari);
    }
}
?>

<div class="container">
    <div class="page-title">Profil Saya</div>
    <div class="page-sub">Kelola informasi identitas kamu</div>

    <?php if ($pesan_sukses): ?>
        <div class="alert alert-sukses"><?= $pesan_sukses ?></div>
    <?php endif; ?>
    <?php if ($pesan_error): ?>
        <div class="alert alert-error"><?= $pesan_error ?></div>
    <?php endif; ?>

    <div class="form-row">
        <div>
            <div class="kotak">
                <div class="kotak-judul">Informasi Pribadi</div>
                <form method="POST">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= $user['nama'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?= $user['email'] ?>" disabled style="background:#f1f5f9; color:#94a3b8">
                        <p class="teks-kecil teks-abu mt-10">Email tidak bisa diubah</p>
                    </div>
                    <div class="form-group">
                        <label>No HP</label>
                        <input type="text" name="no_hp" value="<?= $user['no_hp'] ?>" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="form-group">
                        <label>No KTP</label>
                        <input type="text" name="no_ktp" value="<?= $user['no_ktp'] ?>" placeholder="16 digit NIK">
                    </div>
                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <textarea name="alamat" placeholder="Jalan, kota, provinsi..."><?= $user['alamat'] ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-biru btn-full">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <div>
            <div class="kotak">
                <div class="kotak-judul">Ubah Password</div>
                <form method="POST">
                    <!-- kirim juga data lain supaya tidak ter-reset -->
                    <input type="hidden" name="nama" value="<?= $user['nama'] ?>">
                    <input type="hidden" name="no_hp" value="<?= $user['no_hp'] ?>">
                    <input type="hidden" name="alamat" value="<?= $user['alamat'] ?>">
                    <input type="hidden" name="no_ktp" value="<?= $user['no_ktp'] ?>">

                    <div class="form-group">
                        <label>Password Lama</label>
                        <input type="password" name="password_lama" placeholder="Masukkan password lama">
                    </div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password_baru" placeholder="Min 6 karakter">
                    </div>
                    <button type="submit" class="btn btn-kuning btn-full">Ganti Password</button>
                </form>
            </div>

            <!-- Info akun -->
            <div class="kotak">
                <div class="kotak-judul">Info Akun</div>
                <div class="info-baris">
                    <span class="label">Role</span>
                    <span class="nilai"><span class="status status-disetujui"><?= ucfirst($user['role']) ?></span></span>
                </div>
                <div class="info-baris">
                    <span class="label">Bergabung</span>
                    <span class="nilai"><?= date('d M Y', strtotime($user['created_at'])) ?></span>
                </div>
                <?php
                    $total_sewa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM penyewaan WHERE user_id = '$user_id'"));
                ?>
                <div class="info-baris">
                    <span class="label">Total Penyewaan</span>
                    <span class="nilai"><?= $total_sewa['jml'] ?> kali</span>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
