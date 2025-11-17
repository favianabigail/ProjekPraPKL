<?php
session_start();
include "koneksi.php";

// Ambil data dari session
$cart = $_SESSION['cart'] ?? [];
$nama = $_SESSION['nama'] ?? 'Guest';
$meja = $_SESSION['meja'] ?? '-';

// Hitung total
$total = 0;
foreach ($cart as $item) {
    if (isset($item['harga'], $item['quantity'])) {
        $total += $item['harga'] * $item['quantity'];
    }
}
$_SESSION['total'] = $total;

// Simpan list nama menu (untuk ringkasan)
$menuList = [];
foreach ($cart as $item) {
    if (isset($item['nama'], $item['quantity'])) {
        $menuList[] = $item['nama'] . " (" . $item['quantity'] . "x)";
    }
}
$_SESSION['menu'] = implode(', ', $menuList);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi</title>
    <link rel="stylesheet" href="transaksi.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Rincian Transaksi</h2>
            <p>Nama: <strong><?= htmlspecialchars($nama) ?></strong></p>
            <p>Meja: <strong><?= htmlspecialchars($meja) ?></strong></p>

            <ul>
                <?php if (!empty($cart)) { ?>
                    <?php foreach ($cart as $item): ?>
                        <li>
                            <?= htmlspecialchars($item['nama']) ?> 
                            (<?= (int)$item['quantity'] ?>x) - 
                            Rp <?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?>
                        </li>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <li>Keranjang kosong</li>
                <?php } ?>
            </ul>

            <p><strong>Total: Rp <?= number_format($total, 0, ',', '.') ?></strong></p>
        </div>

        <div class="bukti">
            <form action="upload_bukti.php" method="POST">
                <label for="metode">Pilih Metode Pembayaran:</label><br>
                <select name="metode" id="metode" required>
                    <option value="">-- Pilih --</option>
                    <option value="OVO">OVO</option>
                    <option value="DANA">DANA</option>
                    <option value="GoPay">GoPay</option>
                    <option value="ShopeePay">ShopeePay</option>
                </select><br><br>
                <button type="submit">Lanjut Upload Bukti</button>
            </form>
        </div>
    </div>
</body>
</html>
