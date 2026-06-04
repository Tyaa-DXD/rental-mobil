<?php
session_start();
include 'koneksi.php';
if(!isset($_SESSION['user'])) header("Location: login.php");

$merek = $_GET['merek'];
$data = mysqli_query($conn,"SELECT * FROM $merek");
?>
<link rel="stylesheet" href="css/style.css">

<div class="container">
<h2>Daftar Mobil <?= strtoupper($merek) ?></h2>

<?php while($d = mysqli_fetch_assoc($data)){ ?>
<div class="card">
<h3><?= $d['nama_mobil'] ?></h3>
<p>Harga / hari: Rp<?= number_format($d['harga_per_hari']) ?></p>

<input type="number" id="hari<?= $d['id'] ?>" placeholder="Jumlah hari">
<button onclick="hitung(<?= $d['harga_per_hari'] ?>, <?= $d['id'] ?>)">Hitung Total</button>
<p id="total<?= $d['id'] ?>"></p>
</div>
<?php } ?>

<script>
function hitung(harga, id){
    var hari = document.getElementById('hari'+id).value;
    var total = harga * hari;
    document.getElementById('total'+id).innerHTML =
        "Total: Rp" + total.toLocaleString();
}
</script>

</div>