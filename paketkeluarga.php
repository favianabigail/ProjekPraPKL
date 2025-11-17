<?php
session_start();
include "koneksi.php";
include "header.php";

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('login dulu'); window.location.href='login.php';</script>";
    exit;

}

$sql= "SELECT * FROM menu WHERE kategori = 'paketkeluarga'";
$query= mysqli_query($koneksi,$sql);
$keyword = $_GET['keyword'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paket Keluarga</title>
    <link rel="stylesheet" href="paketkeluarga.css">
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
            <div class="grid-container">
                                    <?php while($snack = mysqli_fetch_assoc($query)) { ?>
    <div class="grid-item">
        <img src="img/<?= $snack['link_gambar'] ?>" alt="">
        <h3><?= $snack['nama'] ?></h3>
        <p>Rp. <?= $snack['harga'] ?></p>
        
        <?php if ($snack['stok'] > 0): ?>
            <a href="pesan.php?id_menu=<?= $snack['id_menu'] ?>">
                <button class="btn-order">Pesan</button>
            </a>
        <?php else: ?>
            <button class="btn-disabled" disabled>Stok habis</button>
        <?php endif; ?>
    </div>
<?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
