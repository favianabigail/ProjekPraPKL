<?php
session_start();
include "koneksi.php";
include "header.php";

// Cek login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Login dulu'); window.location.href='login.php';</script>";
    exit;
}

// Ambil keyword dari URL
$keyword = $_GET['keyword'] ?? '';

// Query cari semua menu berdasarkan nama atau kategori (dengan escaping untuk keamanan)
$keyword_escaped = mysqli_real_escape_string($koneksi, $keyword);
$sql = "SELECT * FROM menu 
        WHERE (nama LIKE '%$keyword_escaped%' 
        OR kategori LIKE '%$keyword_escaped%')
        AND stok > 0
        ORDER BY kategori, nama";
$result = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian</title>
    <link rel="stylesheet" href="snack.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
<div class="container">
    <div class="sidebar">
        <form method="GET" action="search.php" class="search-form">
            <input type="text" name="keyword" placeholder="Search Menu..." value="<?= htmlspecialchars($keyword) ?>" required>
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
        <ul>
            <li><a href="snack.php">Snack</a></li>
            <li><a href="makanan.php">Makanan</a></li>
            <li><a href="minuman.php">Minuman</a></li>
            <li><a href="paketkeluarga.php">Paket Sekeluarga</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2 style="margin-bottom: 20px;"></h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="grid-container">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="grid-item">
                        <img src="img/<?= htmlspecialchars($row['link_gambar']) ?>" alt="<?= htmlspecialchars($row['nama']) ?>">
                        <h3><?= htmlspecialchars($row['nama']) ?></h3>
                        <p>Rp. <?= number_format($row['harga'], 0, ',', '.') ?></p>
                        
                        <?php if ($row['stok'] > 0): ?>
                            <a href="pesan.php?id_menu=<?= $row['id_menu'] ?>">
                                <button class="btn-order">Pesan</button>
                            </a>
                        <?php else: ?>
                            <button class="btn-disabled" disabled>Stok habis</button>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="text-align: center; margin-top: 50px; font-size: 18px;">
                Tidak ditemukan menu dengan kata kunci "<strong><?= htmlspecialchars($keyword) ?></strong>".
            </p>
            <p style="text-align: center;">
                <a href="snack.php" style="color: #f57c00; text-decoration: underline;">Kembali ke Menu</a>
            </p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>