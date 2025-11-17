<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Login dulu'); window.location.href='login.php';</script>";
    exit;
}

include "koneksi.php";
include "header.php";
$id = $_SESSION['id_user'];
$username = $_SESSION['nama'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
    <!-- <header id="Home">
        <nav>
            <div class="logo">
                <img src="img/logo2.png" alt="logo">
            </div>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="snack.php">Order</a></li>
            </ul>

            <div class="cart"> 
    <?php if (isset($_SESSION['nama'])): ?>
        <a href="logout.php" class="login-text">Logout</a>
    <?php else: ?>
        <a href="login.php" class="login-text">Login</a>
    <?php endif; ?>
    <a href="pemesanan.php"><i class="fa-solid fa-cart-shopping"></i></a>
</div>

        </nav>
    </header> -->

    <main>
        <h1>Our Menus</h1>
        <div class="menu-container">
            <div class="menu-item">
                <h2>Snack</h2>
                <img src="img/snack.jpeg" alt="Snack">
                <button onclick="location.href='snack.php'">Order</button>
            </div>
            <div class="menu-item">
                <h2>Paket Keluarga</h2>
                <img src="img/paketkeluarga.jpeg" alt="">
                <button onclick="location.href='paketkeluarga.php'">Order</button>
            </div>
            <div class="menu-item">
                <h2>Minuman</h2>
                <img src="img/minuman.jpeg" alt="">
                <button onclick="location.href='minuman.php'">Order</button>
            </div>
            <div class="menu-item">
                <h2>Makanan</h2>
                <img src="img/Nasi Goreng.jpeg" alt="">
                <button onclick="location.href='makanan.php'">Order</button>
            </div>
        </div>
    </main>
</body>
</html>