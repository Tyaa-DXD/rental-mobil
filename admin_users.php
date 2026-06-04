<?php
$judul = "Data User";
include 'navbar_admin.php';
include 'koneksi.php';

$pesan_sukses = "";

// hapus user
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    // jangan hapus admin
    $cek = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT role FROM users WHERE id='$id_hapus'"));
    if ($cek['role'] == 'admin') {
        // skip
    } else {
        mysqli_query($koneksi, "DELETE FROM users WHERE id='$id_hapus'");
        $pesan_sukses = "User berhasil dihapus.";
    }
}

$cari_nama = isset($_GET['cari']) ? $_GET['cari'] : '';
$where = "WHERE role = 'user'";
if ($cari_nama) $where .= " AND nama LIKE '%$cari_nama%'";

$users = mysqli_query($koneksi, "SELECT * FROM users $where ORDER BY created_at DESC");
?>

<div class="page-title">Data User</div>
<div class="page-sub">Semua pengguna yang terdaftar</div>

<?php if ($pesan_sukses): ?>
    <div class="alert alert-sukses"><?= $pesan_sukses ?></div>
<?php endif; ?>

<div class="flex gap-10 mb-20">
    <form method="GET" class="flex gap-10">
        <input type="text" name="cari" value="<?= $cari_nama ?>" placeholder="Cari nama user..." style="padding:9px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px;">
        <button type="submit" class="btn btn-biru">Cari</button>
        <?php if ($cari_nama): ?>
            <a href="admin_users.php" class="btn btn-abu">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="kotak" style="padding:0">
    <div class="tabel-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>No KTP</th>
                    <th>Bergabung</th>
                    <th>Total Sewa</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; while($u = mysqli_fetch_assoc($users)): ?>
                <?php
                    $total = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM penyewaan WHERE user_id = '{$u['id']}'"));
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <div class="bold"><?= $u['nama'] ?></div>
                        <?php if (!$u['no_ktp']): ?>
                            <div class="teks-kecil" style="color:var(--merah)">⚠ KTP belum diisi</div>
                        <?php endif; ?>
                    </td>
                    <td class="teks-kecil"><?= $u['email'] ?></td>
                    <td><?= $u['no_hp'] ?: '-' ?></td>
                    <td class="teks-kecil"><?= $u['no_ktp'] ?: '<span style="color:var(--abu-tua)">-</span>' ?></td>
                    <td class="teks-kecil"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                    <td><span class="bold"><?= $total['jml'] ?></span> kali</td>
                    <td>
                        <a href="admin_users.php?hapus=<?= $u['id'] ?>" class="btn btn-kecil btn-merah" onclick="return confirm('Hapus user ini?')">Hapus</a>
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
