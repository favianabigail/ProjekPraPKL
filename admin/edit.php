<?php
include '../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM menu WHERE id_menu = $id";
    $result = $koneksi->query($query);

    if ($result && $result->num_rows > 0) {
        $menu = $result->fetch_assoc();
    } else {
        echo "Menu tidak ditemukan.";
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $koneksi->real_escape_string($_POST['nama']);
    $kategori = $koneksi->real_escape_string($_POST['kategori']);
    $stok = (int)$_POST['stok'];
    $harga = (int)$_POST['harga'];
    
    
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    if ($gambar) {
        $target = '../img/' . basename($gambar);
        move_uploaded_file($tmp, $target);
    } else {
        $gambar = $_POST['gambar_lama']; 
    }

    $sql = "UPDATE menu SET nama='$nama', kategori='$kategori', stok=$stok, harga=$harga, link_gambar='$gambar' WHERE id_menu=$id";

    if ($koneksi->query($sql)) {
        echo "<script>alert('Data berhasil diupdate!'); window.location.href='menu.php';</script>";
    } else {
        echo "Gagal update data: " . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Menu</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
<div class="container">
    <main>
        <h2>Edit Menu</h2>
        <form method="POST" enctype="multipart/form-data">
            <label>Nama Makanan</label><br>
            <input type="text" name="nama" value="<?= $menu['nama'] ?>" required><br><br>

            <label>Kategori</label><br>
            <select name="kategori" required>
                <option value="makanan" <?= $menu['kategori'] == 'makanan' ? 'selected' : '' ?>>Makanan</option>
                <option value="minuman" <?= $menu['kategori'] == 'minuman' ? 'selected' : '' ?>>Minuman</option>
                <option value="snack" <?= $menu['kategori'] == 'snack' ? 'selected' : '' ?>>Snack</option>
                <option value="paketkeluarga" <?= $menu['kategori'] == 'paketkeluarga' ? 'selected' : '' ?>>Paket keluarga</option>
            </select><br><br>

            <label>Stok</label><br>
            <input type="number" name="stok" value="<?= $menu['stok'] ?>" required><br><br>

            <label>Harga</label><br>
            <input type="number" name="harga" value="<?= $menu['harga'] ?>" required><br><br>

            <label>Gambar</label><br>
            <input type="file" name="gambar"><br>
            <img src="../img/<?= $menu['link_gambar'] ?>" alt="Gambar lama" width="100"><br>
            <input type="hidden" name="gambar_lama" value="<?= $menu['link_gambar'] ?>"><br><br>


            <button type="submit">Simpan Perubahan</button>
        </form>
    </main>
</div>
</body>
</html>
