<?php
include 'koneksi.php';
if(isset($_POST['register'])){
 $nama=$_POST['nama'];
 $email=$_POST['email'];
 $pass=password_hash($_POST['password'],PASSWORD_DEFAULT);

 mysqli_query($conn,"INSERT INTO users VALUES('','$nama','$email','$pass')");
 header("Location: login.php");
}
?>
<link rel="stylesheet" href="css/style.css">
<div class="center-box">
<h2 align="center">Register</h2>
<form method="post">
<input type="text" name="nama" placeholder="Nama" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button name="register">Daftar</button>
<a href="login.php">Sudah punya akun?</a>
</form>
</div>