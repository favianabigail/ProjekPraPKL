<?php
include '../koneksi.php';
session_start();

// Cek login admin
// if (!isset($_SESSION['id_user'])) {
//     echo "<script>alert('Silakan login terlebih dahulu'); window.location.href='login.php';</script>";
//     exit;
// }

// Ambil semua data dari tabel laporan
$result = mysqli_query($koneksi, "SELECT * FROM laporan ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <link rel="stylesheet" href="admin_t.css">
    <style>
        /* Style untuk badge status */
        .status-badge {
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 13px;
            display: inline-block;
        }

        .status-diterima {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="admin_verifikasi.php">Verifikasi</a></li>
            <li><a href="admin_transaksi.php">Status</a></li>
            <li><a href="laporan.php">Laporan</a></li>
            <li><a href="menu.php">Menu</a></li>
        </ul>
    </nav>

    <!-- Konten utama -->
    <div class="container">
        <!-- <a href="logout.php" class="logout-button">Logout</a> -->
        <h2>Laporan Transaksi Selesai</h2>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Meja</th>
                    <th>Menu</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Waktu Selesai</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['kode_order']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['no_meja']) ?></td>
                        <td><?= htmlspecialchars($row['menu']) ?></td>
                        <td>Rp <?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($row['metode']) ?></td>
                        <td>
                            <span class="status-badge <?= $row['status'] == 'Ditolak' ? 'status-ditolak' : 'status-diterima' ?>">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                        </td>
                        <td><?= $row['waktu_selesai'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>