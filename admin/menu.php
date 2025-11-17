<?php
session_start();
include '../koneksi.php';
$menu = [];
$sql = "SELECT * FROM menu";
$username = $_SESSION['nama'];
$result = $koneksi->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menu[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="menu.css">
  <title>Menu</title>
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
    <main>
        <!-- <header>
            <h1>Hallo, <?= htmlspecialchars($username) ?></h1>
            <a href="logout.php" class= "logout">logout</a>
        </header> -->
        <br><br>
        <h2>Daftar Menu Makanan</h2>
        <a href="tambah.php" class="btn-tambah">Tambah Makanan</a>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Nama Makanan</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($menu as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><img src="../img/<?= htmlspecialchars($item['link_gambar']) ?>" alt="<?= htmlspecialchars($item['nama']) ?>"></td>

                    <td><?= htmlspecialchars($item['nama']) ?></td>
                    <td><?= htmlspecialchars($item['kategori']) ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td><?= $item['stok'] ?></td>
                    <td>
                        <a href="edit.php?id=<?= $item['id_menu'] ?>" class="btn-edit">Edit</a>
                        <a href="hapus.php?id=<?= $item['id_menu'] ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </main>
  </div>
</body>
</html>
