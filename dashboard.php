<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>LuxuryCar Rent Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #0d0d0d;
            color: #fff;
        }

        header {
            background: #111;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: gold;
        }

        nav a {
            color: #ddd;
            text-decoration: none;
            margin-left: 20px;
        }

        nav a:hover {
            color: gold;
        }

        .hero {
            padding: 60px 40px;
            text-align: center;
            background: linear-gradient(to right, #111, #1a1a1a);
        }

        .hero h1 {
            font-size: 36px;
            color: gold;
        }

        .hero p {
            max-width: 800px;
            margin: 20px auto;
            color: #ccc;
        }

        .brand-section {
            padding: 30px 40px;
        }

        .brand-section h2 {
            margin-bottom: 15px;
            color: gold;
        }

        .car-scroll {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .car-card {
            min-width: 250px;
            background: #1a1a1a;
            border-radius: 10px;
            padding: 15px;
            flex-shrink: 0;
            transition: transform 0.3s;
        }

        .car-card:hover {
            transform: scale(1.05);
        }

        .car-card img {
            width: 100%;
            border-radius: 10px;
        }

        .car-card h3 {
            margin: 10px 0;
        }

        .car-card span {
            display: block;
            margin-top: 10px;
            color: gold;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding: 20px;
            background: #111;
            border-top: 1px solid #333;
            margin-top: 40px;
        }
    </style>
</head>

<body>

    <header>
        <div class="logo">LuxuryCar Rent</div>
        <nav>
            <a href="#">Home</a>
            <a href="#">Cars</a>
            <a href="#">Booking</a>
            <a href="#">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>

        <!-- HERO -->
        <section class="hero">
            <h1>Rasakan Sensasi Mengendarai Mobil Impian</h1>
            <p>
                LuxuryCar Rent menyediakan berbagai pilihan mobil premium dari sport car,
                supercar, hingga mobil keluarga dengan kondisi terbaik dan harga fleksibel.
                Sewa harian, mingguan, atau bulanan sesuai kebutuhan Anda.
            </p>
        </section>

        <!-- BMW -->
        <section class="brand-section">
            <h2>BMW Series</h2>
            <div class="car-scroll">
                <div class="car-card">
                    <img src="img/bmw_m4.jpg">
                    <h3>BMW M4 Coupe</h3>
                    <p>Mesin 3.0L Twin Turbo, 503 HP, 0-100 km/h 3.8 detik</p>
                    <span>Rp 5.000.000 / hari</span>
                </div>
                <div class="car-card">
                    <img src="img/bmw_x7.jpg">
                    <h3>BMW X7</h3>
                    <p>SUV mewah 7 penumpang, panoramic roof, nyaman keluarga</p>
                    <span>Rp 3.500.000 / hari</span>
                </div>
            </div>
        </section>

        <!-- Lamborghini -->
        <section class="brand-section">
            <h2>Lamborghini</h2>
            <div class="car-scroll">
                <div class="car-card">
                    <img src="img/lambo_huracan.jpg">
                    <h3>Lamborghini Huracan</h3>
                    <p>V10 Engine, 602 HP, supercar dengan desain futuristik</p>
                    <span>Rp 12.000.000 / hari</span>
                </div>
                <div class="car-card">
                    <img src="img/lambo_urus.jpg">
                    <h3>Lamborghini Urus</h3>
                    <p>SUV super cepat, kombinasi sport & keluarga</p>
                    <span>Rp 9.000.000 / hari</span>
                </div>
            </div>
        </section>

        <!-- Audi -->
        <section class="brand-section">
            <h2>Audi</h2>
            <div class="car-scroll">
                <div class="car-card">
                    <img src="img/audi_r8.jpg">
                    <h3>Audi R8</h3>
                    <p>V10 Quattro, performa tinggi, handling presisi</p>
                    <span>Rp 8.000.000 / hari</span>
                </div>
                <div class="car-card">
                    <img src="img/audi_q7.jpg">
                    <h3>Audi Q7</h3>
                    <p>SUV elegan, teknologi canggih, cocok keluarga</p>
                    <span>Rp 3.000.000 / hari</span>
                </div>
            </div>
        </section>

    </main>

    <footer>
        <p>© 2026 LuxuryCar Rent | Premium Car Rental Service</p>
    </footer>

</body>

</html>