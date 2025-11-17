<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['id_admin'])) {
    echo "<script>alert('Login dulu sebagai admin'); window.location.href='login_admin.php';</script>";
    exit;
}
$result = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE status_bukti = 'diterima' AND status_proses != 'Selesai' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Transaksi</title>
    <link rel="stylesheet" href="admin_t.css"> <!-- pastikan nama file CSS sesuai -->
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
        <!-- Logout button -->
            <!-- <a href="logout.php" class="logout-button">Logout</a> -->

        <h2>Data Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Meja</th>
                    <th>Menu</th>
                    <th>Total</th>
                    <th>Status Proses</th>
                    <th>Update Status</th>
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
                        <td><?= htmlspecialchars($row['status_proses']) ?></td>
                        <td>
                            <form action="status_proses.php" method="POST">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <select name="status_proses">
                                    <option value="Diproses" <?= $row['status_proses'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                                    <option value="Dimasak" <?= $row['status_proses'] == 'Dimasak' ? 'selected' : '' ?>>Dimasak</option>
                                    <option value="Diantar ke Meja" <?= $row['status_proses'] == 'Diantar ke Meja' ? 'selected' : '' ?>>Diantar ke Meja</option>
                                    <option value="Selesai" <?= $row['status_proses'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
