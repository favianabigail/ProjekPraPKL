<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $koneksi->real_escape_string($_POST['nama']);
    $kategori = $koneksi->real_escape_string($_POST['kategori']);
    $stok = (int)$_POST['stok'];
    $harga = (int)$_POST['harga'];

    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    if ($gambar) {
        // Pastikan folder img/ sudah ada
        if (!is_dir('img')) {
            mkdir('img', 0777, true);
        }

        // Hindari karakter tidak valid dan nama duplikat
        $gambar_sanitized = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $gambar);
        $target = '../img/' . $gambar_sanitized;

        if (move_uploaded_file($tmp, $target)) {
            $sql = "INSERT INTO menu (nama, kategori, stok, harga, link_gambar)
                    VALUES ('$nama', '$kategori', $stok, $harga, '$gambar_sanitized')";
            if ($koneksi->query($sql)) {
                echo "<script>alert('Menu berhasil ditambahkan!'); window.location.href='menu.php';</script>";
            } else {
                echo "Gagal menyimpan data: " . $koneksi->error;
            }
        } else {
            echo "Gagal mengunggah gambar.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Menu</title>
    <link rel="stylesheet" href="tambah.css">
</head>
<body>
    <div class="container">
        <h2>Tambah Menu</h2>
        <form action="tambah.php" method="POST" enctype="multipart/form-data">
            <label for="nama">Nama Menu</label>
            <input type="text" id="nama" name="nama" required>

            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori" required>
                <option value="snack">Snack</option>
                <option value="makanan">Makanan</option>
                <option value="minuman">Minuman</option>
                <option value="paketkeluarga">Paket keluarga</option>
            </select>

            <label for="stok">Stok</label>
            <input type="number" id="stok" name="stok" required>

            <label for="harga">Harga</label>
            <input type="number" id="harga" name="harga" required>

            <label for="gambar">Gambar</label>
            <input type="file" id="gambar" name="gambar" accept="image/*" required>

            <input type="submit" value="Tambah Menu">
        </form>
    </div>
</body>
</html>
