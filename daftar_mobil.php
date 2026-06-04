<?php
$judul = "Daftar Mobil";
include 'navbar_user.php';
include 'koneksi.php';

// filter pencarian
$cari    = isset($_GET['cari']) ? $_GET['cari'] : '';
$filter  = isset($_GET['transmisi']) ? $_GET['transmisi'] : '';
$urut    = isset($_GET['urut']) ? $_GET['urut'] : 'id';

$where = "WHERE status = 'tersedia'";
if ($cari) $where .= " AND nama LIKE '%$cari%'";
if ($filter) $where .= " AND transmisi = '$filter'";

$order = "ORDER BY id DESC";
if ($urut == 'harga_asc') $order = "ORDER BY harga_per_hari ASC";
if ($urut == 'harga_desc') $order = "ORDER BY harga_per_hari DESC";

$mobil = mysqli_query($koneksi, "SELECT * FROM mobil $where $order");
$jumlah = mysqli_num_rows($mobil);
?>

<div class="container">
    <div class="page-title">Daftar Mobil</div>
    <div class="page-sub">Pilih mobil yang sesuai kebutuhan kamu</div>

    <!-- Form filter -->
    <div class="kotak mb-20">
        <form method="GET" class="flex gap-10" style="flex-wrap:wrap">
            <input type="text" name="cari" value="<?= $cari ?>" placeholder="Cari nama mobil..." style="padding:9px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; min-width:200px;">
            <select name="transmisi" style="padding:9px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px;">
                <option value="">Semua Transmisi</option>
                <option value="Manual" <?= $filter=='Manual'?'selected':'' ?>>Manual</option>
                <option value="Automatic" <?= $filter=='Automatic'?'selected':'' ?>>Automatic</option>
            </select>
            <select name="urut" style="padding:9px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px;">
                <option value="id" <?= $urut=='id'?'selected':'' ?>>Terbaru</option>
                <option value="harga_asc" <?= $urut=='harga_asc'?'selected':'' ?>>Harga Terendah</option>
                <option value="harga_desc" <?= $urut=='harga_desc'?'selected':'' ?>>Harga Tertinggi</option>
            </select>
            <button type="submit" class="btn btn-biru">Cari</button>
            <?php if ($cari || $filter): ?>
                <a href="daftar_mobil.php" class="btn btn-abu">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <p class="teks-abu mb-20 teks-kecil"><?= $jumlah ?> mobil ditemukan</p>

    <?php if ($jumlah == 0): ?>
        <div class="text-center" style="padding:60px 0; color:var(--abu-tua)">
            <div style="font-size:50px">🔍</div>
            <p>Tidak ada mobil yang sesuai</p>
        </div>
    <?php else: ?>
    <div class="grid-mobil">
        <?php while($m = mysqli_fetch_assoc($mobil)): ?>
        <div class="kartu-mobil">
            <div class="foto-mobil">
                <?php if ($m['foto']): ?>
                    <img src="uploads/<?= $m['foto'] ?>" alt="<?= $m['nama'] ?>">
                <?php else: ?>
                    🚗
                <?php endif; ?>
            </div>
            <div class="isi-kartu">
                <div class="nama-mobil"><?= $m['nama'] ?></div>
                <div class="info-kecil">
                    <span class="badge">📅 <?= $m['tahun'] ?></span>
                    <span class="badge">⚙️ <?= $m['transmisi'] ?></span>
                    <span class="badge">👥 <?= $m['kapasitas'] ?> kursi</span>
                    <span class="badge">🎨 <?= $m['warna'] ?></span>
                </div>
                <div class="harga">
                    Rp <?= number_format($m['harga_per_hari'], 0, ',', '.') ?>
                    <span>/ hari</span>
                </div>
                <a href="detail_mobil.php?id=<?= $m['id'] ?>" class="btn btn-biru btn-full">Lihat Detail</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>

</div>
</body>
</html>
