<?php
session_start();
include 'koneksi.php';
include 'header.php';

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit;
}

// ✅ GUNAKAN ID_USER, BUKAN NAMA
$id_user = (int)$_SESSION['id_user'];

// ✅ Query berdasarkan id_user
$query = mysqli_query($koneksi, "
    SELECT t.*, 
           IFNULL(SUM(d.jumlah), 0) AS total_item
    FROM transaksi t
    LEFT JOIN transaksi_detail d ON t.kode_order = d.kode_order
    WHERE t.id_user = $id_user
    GROUP BY t.kode_order
    ORDER BY t.tanggal DESC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan</title>
    
     <style>
body {
    font-family: Arial;
    background-color: #fdf6e3;
    margin: 0;
    padding-top: 0px;
}

header{
    width: 100%;
    height: 16vh;
}

header nav{
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    right: 0;
    left: 0;
    background: #333;
    box-shadow: 0 0 10px rgba(0, 0, 0, 5);
    z-index: 1000;
}

header nav .logo img{
    width: 90px;
    cursor: pointer;
    margin: 0;
    display: flex;
}

header nav ul {
    list-style: none;
    display: flex; 
    justify-content: center; 
    flex: 1; 
    padding: 0;
    margin: 0;
}

header nav ul li{
    display: inline-block;
    margin: 0 15px;
}

header nav ul li a{
    text-decoration: none;
    color: white;
    font-weight: 500;
    font-size: 20px;
}

header nav ul li a::after{
    color: '';
    width: 100%;
    height: 2px;
    background: #fac031;
    transition: 0.2s linear;
}

header nav ul li a:hover::after{
    width: 100%;
}

header nav ul li a:hover{
    color: #fac031;
}

h2 {
    color: #333;
    text-align: center;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 0 10px #ccc;
}

th, td {
    padding: 12px;
    border: 1px solid #eee;
    text-align: center;
}

th {
    background-color: #f57c00;
    color: white;
}

/* CSS modern untuk button detail */
td button {
    background: linear-gradient(135deg, #f57c00, #ff9800); /* Gradient oranye */
    border: none;
    border-radius: 8px;
    padding: 0;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 3px 6px rgba(245, 124, 0, 0.3);
}

td button:hover {
    background: linear-gradient(135deg, #e65100, #f57c00);
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(245, 124, 0, 0.4);
}

td button .detail {
    color: white;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    padding: 10px 20px;
    display: inline-block;
    border-radius: 8px;
}    
</style>
</head>
<body>
    <h2>Riwayat Pesanan Saya</h2>
    <table>
        <tr>
            <th>Kode Order</th>
            <th>No Meja</th>
            <th>Menu</th>
            <th>Subtotal</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Detail</th>
        </tr>
        <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($query)): ?>
                <?php
                    $tanggal = date("d-m-Y H:i", strtotime($row['tanggal']));
                    $status_tampil = ($row['status_bukti'] == 'Ditolak') ? 'Ditolak' : $row['status_proses'];

                    // Hitung jumlah item di detail pesanan
                    $kode_order = mysqli_real_escape_string($koneksi, $row['kode_order']);
                    $q_jumlah = mysqli_query($koneksi, "SELECT SUM(jumlah) AS total_item FROM transaksi_detail WHERE kode_order='$kode_order'");
                    $jumlah_item = 0;
                    if ($q_jumlah && $data_jumlah = mysqli_fetch_assoc($q_jumlah)) {
                        $jumlah_item = (int)$data_jumlah['total_item'];
                    }
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['kode_order']) ?></td>
                    <td><?= htmlspecialchars($row['no_meja']) ?></td>
                    <td><?= $jumlah_item ?> item</td>
                    <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                    <td><?= ucfirst(htmlspecialchars($row['metode'])) ?></td>
                    <td><?= htmlspecialchars($status_tampil) ?></td>
                    <td><?= $tanggal ?></td>
                    <td><button><a class="detail" href="detail_pesanan.php?kode_order=<?= urlencode($row['kode_order']) ?>">Detail</a></button></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">Belum ada pesanan</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>