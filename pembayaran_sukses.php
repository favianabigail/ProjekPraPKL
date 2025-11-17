<?php
session_start();
include "koneksi.php";


$kode_order = isset($_GET['kode']) ? $koneksi->real_escape_string($_GET['kode']) : '';

$query = "SELECT * FROM transaksi WHERE kode_order = '$kode_order'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
} else {
    echo "<p style='color: red; text-align: center;'>Transaksi tidak ditemukan.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Berhasil</title>
    <link rel="stylesheet" href="transaksi.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="img/logo2.png" alt="logo">
            </div>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="snack.php">Order</a></li>
            </ul>
        </nav>
    </header>

    <div class="modal">
        <h1>Pembayaran Berhasil!</h1><br>

        <h2>Detail Transaksi</h2>
        <div class="transaction-detail">
            <span><strong>Kode Order:</strong></span>
            <span><?= htmlspecialchars($data['kode_order']) ?></span>
        </div>
        <div class="transaction-detail">
            <span><strong>Nama Pemesan:</strong></span>
            <span><?= htmlspecialchars($data['nama']) ?></span>
        </div>
        <div class="transaction-detail">
            <span><strong>Nomor Meja:</strong></span>
            <span><?= htmlspecialchars($data['no_meja']) ?></span>
        </div>
        <div class="transaction-detail">
            <span><strong>Metode Pembayaran:</strong></span>
            <span><?= htmlspecialchars($data['metode']) ?></span>
        </div>
        <div class="transaction-detail">
            <span><strong>Total:</strong></span>
            <span>Rp <?= number_format($data['subtotal'], 0, ',', '.') ?></span>
        </div>
        <div class="transaction-detail">
            <span><strong>Tanggal:</strong></span>
            <span><?= htmlspecialchars($data['tanggal']) ?></span>
        </div>
        <div class="transaction-detail">
            <span><strong>Jam:</strong></span>
            <span><?= htmlspecialchars($data['jam']) ?></span>
        </div><br><br>

        <a href="kasir.php"><button class="close-btn">Kembali ke Kasir</button></a>
        <button class="btn-cetak" onclick="window.print()">Cetak Struk</button>
    </div>

    
</body>
</html>
