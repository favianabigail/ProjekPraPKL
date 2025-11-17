<?php
session_start();
include "koneksi.php";

$kode = $_SESSION['kode_order'] ?? '-';
$status_proses = 'selesai';

if ($kode !== '-') {
    $query = "SELECT status_proses FROM transaksi WHERE kode_order = '$kode' LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    if ($data = mysqli_fetch_assoc($result)) {
        $status_proses = $data['status_proses'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Status Pembayaran</title>
    <link rel="stylesheet" href="pembayaran.css">
</head>
<body>
    <div class="kembali">
        <h2>Terima Kasih!</h2>
        <p>Kode Pesanan Anda: <strong><?= htmlspecialchars($kode) ?></strong></p>
        <p>Bukti pembayaran Anda telah kami terima dan sedang diverifikasi.</p>
        
        <h3>Status Pesanan:</h3><p><strong><?= htmlspecialchars($status_proses) ?></strong></p>
        
        
        <a href="home.php">Kembali ke Beranda</a>
    </div>
</body>
</html>
