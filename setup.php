<?php
// JALANKAN FILE INI SEKALI SAJA untuk buat akun admin default
// Setelah berhasil, hapus file ini!

include 'koneksi.php';

// buat folder uploads kalau belum ada
if (!is_dir('uploads')) {
    mkdir('uploads', 0755, true);
    echo "✅ Folder uploads dibuat<br>";
}

// cek admin sudah ada belum
$cek = mysqli_query($koneksi, "SELECT id FROM users WHERE email = 'admin@rental.com'");
if (mysqli_num_rows($cek) > 0) {
    echo "⚠️ Admin sudah ada! <a href='login.php'>Login sekarang</a><br>";
} else {
    $pass = password_hash('admin123', PASSWORD_DEFAULT);
    mysqli_query($koneksi, "INSERT INTO users (nama, email, password, no_hp, role) VALUES ('Admin', 'admin@rental.com', '$pass', '08100000000', 'admin')");
    echo "✅ Akun admin berhasil dibuat!<br>";
    echo "📧 Email: admin@rental.com<br>";
    echo "🔑 Password: admin123<br><br>";
    echo "<strong>⚠️ HAPUS FILE INI SETELAH LOGIN!</strong><br><br>";
    echo "<a href='login.php'>→ Login Sekarang</a>";
}
?>
