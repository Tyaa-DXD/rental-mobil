<?php
$conn = mysqli_connect("localhost","root","","rental_mobil");
if(!$conn){
    die("Koneksi gagal: ".mysqli_connect_error());
}
?>