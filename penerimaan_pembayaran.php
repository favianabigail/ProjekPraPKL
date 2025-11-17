<?php
include 'koneksi.php';

if (isset($_GET['verifikasi']) && isset($_GET['kode'])) {
    $kode = $_GET['kode'];
    $verifikasi = $_GET['verifikasi'];

    if (in_array($verifikasi, ['Diterima', 'Ditolak'])) {
        mysqli_query($koneksi, "UPDATE transaksi SET status_bukti='$verifikasi' WHERE kode_order='$kode'");
    }
}

$result = mysqli_query($koneksi, "SELECT * FROM transaksi ORDER BY tanggal DESC, jam DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Penerimaan Pembayaran (Admin)</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f6f8; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background: #2d3436; color: white; }
        .btn { padding: 6px 10px; text-decoration: none; border-radius: 4px; margin: 2px; }
        .accept { background: #27ae60; color: white; }
        .reject { background: #e74c3c; color: white; }
    </style>
</head>
<body>
<div class="container">
        <nav class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li><a href="penerimaan_pembayaran.php"></a>penerimaan_pembayaran</li>
                <li><a href="laporan.php">Laporan</a></li>
                <li><a href="menu.php">Menu</a></li>
            </ul>
        </nav>
    </div>    
    <h2>Daftar Pembayaran Masuk</h2>
    <table>
        <tr>
            <th>Kode</th><th>Nama</th><th>Meja</th><th>Subtotal</th><th>Metode</th><th>Tanggal</th><th>Jam</th><th>Bukti</th><th>Status</th><th>Aksi</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['kode_order'] ?></td>
            <td><?= $row['nama'] ?></td>
            <td><?= $row['no_meja'] ?></td>
            <td>Rp<?= number_format($row['subtotal'], 0, ',', '.') ?></td>
            <td><?= $row['metode'] ?></td>
            <td><?= $row['tanggal'] ?></td>
            <td><?= $row['jam'] ?></td>
            <td>
                <?php if ($row['bukti_pembayaran']) { ?>
                    <a href="bukti/<?= $row['bukti_pembayaran'] ?>" target="_blank">Lihat</a>
                <?php } else { echo "<em>Belum ada</em>"; } ?>
            </td>
            <td><?= $row['status_bukti'] ?? 'Menunggu' ?></td>
            <td>
                <?php if ($row['status_bukti'] === 'Menunggu') { ?>
                    <a class="btn accept" href="?kode=<?= $row['kode_order'] ?>&verifikasi=Diterima">Terima</a>
                    <a class="btn reject" href="?kode=<?= $row['kode_order'] ?>&verifikasi=Ditolak">Tolak</a>
                <?php } else { echo "-"; } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
