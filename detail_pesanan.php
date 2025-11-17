<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit;
}

if (empty($_GET['kode_order'])) {
    echo "<script>alert('Kode order tidak ditemukan'); window.location.href='riwayat_pesanan.php';</script>";
    exit;
}

$kode_order = mysqli_real_escape_string($koneksi, $_GET['kode_order']);

// Ambil data transaksi utama
$query_transaksi = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE kode_order = '$kode_order'");
$transaksi = mysqli_fetch_assoc($query_transaksi);

$detail = [];
$total = 0;

if ($transaksi) {
    // Ambil data detail menu berdasarkan kode_order
    $query_detail = mysqli_query($koneksi, "SELECT * FROM transaksi_detail WHERE kode_order = '$kode_order'");
    while ($row = mysqli_fetch_assoc($query_detail)) {
        $total += (int)$row['subtotal'];
        $detail[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fdf6e3; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        .info { width: 80%; margin: auto; margin-bottom: 20px; }
        .info p { margin: 5px 0; font-size: 16px; }
        table { width: 80%; margin: auto; border-collapse: collapse; background: #fff; box-shadow: 0 0 10px #ccc; }
        th, td { padding: 12px; border: 1px solid #eee; text-align: center; }
        th { background: #f57c00; color: white; font-size: 16px; }
        td { font-size: 15px; }
        .total { font-weight: bold; background: #fdf1e3; }
        .back { display: block; width: 150px; margin: 20px auto; text-align: center; padding: 10px; background: #f57c00; color: #fff; text-decoration: none; border-radius: 6px; font-size: 16px; }
        .back:hover { background: #e64a19; }
    </style>
</head>
<body>
    <h2>Detail Pesanan - <?= htmlspecialchars($kode_order) ?></h2>

    <?php if ($transaksi): ?>
        <div class="info">
            <p><b>No Meja:</b> <?= htmlspecialchars($transaksi['no_meja']) ?></p>
            <p><b>Tanggal:</b> <?= date("d-m-Y H:i", strtotime($transaksi['tanggal'])) ?></p>
            <p><b>Metode:</b> <?= ucfirst(htmlspecialchars($transaksi['metode'])) ?></p>
            <p><b>Status:</b> 
<?= ($transaksi['status_bukti'] == 'Ditolak') ? 'Ditolak' : htmlspecialchars($transaksi['status_proses']); ?>
</p>

        </div>

        <table>
            <tr>
                <th>Menu</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
            <?php if (!empty($detail)): ?>
                <?php foreach ($detail as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['menu']) ?></td>
                        <td><?= (int)$row['jumlah'] ?></td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total">
                    <td colspan="3">Total</td>
                    <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                </tr>
            <?php else: ?>
                <tr><td colspan="4">Belum ada detail menu</td></tr>
            <?php endif; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center; color:red;">Transaksi tidak ditemukan.</p>
    <?php endif; ?>

    <a class="back" href="riwayat_pesanan.php">← Kembali</a>
</body>
</html>
