    <?php
    include '../koneksi.php';
    session_start();

    if (!isset($_SESSION['id_admin'])) {
        echo "<script>alert('Login dulu sebagai admin'); window.location.href='login_admin.php';</script>";
        exit;
    }
    $username = $_SESSION['nama'];
    $result = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE status_bukti = 'Menunggu' ORDER BY id DESC");
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin - Verifikasi Pembayaran</title>
        <link rel="stylesheet" href="admin_verifikasi.css">
    </head>
    <body>
        <div class="container">
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

            <!-- Main content -->
            <main>
                <!-- Logout button -->
                <!-- <a href="logout.php" class="logout-button">Logout</a> -->

                <h2>Daftar Transaksi Masuk</h2>
                <div class="card">
                    <table>
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Meja</th>
                                <th>Menu</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th>Bukti</th>
                                <th>Status</th>
                                <th>Aksi</th>
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
                                        <?php if ($row['bukti_pembayaran']): ?>
                                        <a href="../bukti/<?= $row['bukti_pembayaran'] ?>" target="_blank">
                                            <img src="../bukti/<?= $row['bukti_pembayaran'] ?>" alt="Bukti" width="100">
                                            </a>
                                        <?php else: ?>
                                            Tidak Ada
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['status_bukti']) ?></td>
                                    <td>
                                        <?php if ($row['status_bukti'] == 'Menunggu'): ?>
                                            <form action="verifikasi.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <input type="hidden" name="aksi" value="Diterima">
                                                <button type="submit">Setujui</button>
                                            </form>
                                            <form action="verifikasi.php" method="POST">
                                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                <input type="hidden" name="aksi" value="Ditolak">
                                                <button type="submit">Tolak</button>
                                            </form>
                                        <?php else: ?>
                                            Sudah Diproses
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </body>
    </html>
