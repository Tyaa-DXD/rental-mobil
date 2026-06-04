<?php
session_start();
include 'koneksi.php';

if(isset($_POST['login'])){
 $email=$_POST['email'];
 $pass=$_POST['password'];

 $data=mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
 $d=mysqli_fetch_assoc($data);

 if($d && password_verify($pass,$d['password'])){
   $_SESSION['user']=$d['nama'];
   header("Location: dashboard.php");
 } else {
   echo "<script>alert('Login gagal');</script>";
 }
}
?>
<link rel="stylesheet" href="css/style.css">
<div class="center-box">
<h2 align="center">Login</h2>
<form method="post">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button name="login">Login</button>
<a href="register.php">Belum punya akun?</a>
</form>
</div>