<?php
include '../koneksi.php';

$pesan = '';
$berhasil = false;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $query = $koneksi->query("SELECT link_gambar FROM menu WHERE id_menu = $id");
    if ($query && $query->num_rows > 0) {
        $data = $query->fetch_assoc();
        $gambar = $data['link_gambar'];
        $path = 'img/' . $gambar;

        if (file_exists($path)) {
            unlink($path);
        }

        $hapus = $koneksi->query("DELETE FROM menu WHERE id_menu = $id");

        if ($hapus) {
            $pesan = "Data berhasil dihapus.";
            $berhasil = true;
        } else {
            $pesan = "Gagal menghapus data: " . $koneksi->error;
        }
    } else {
        $pesan = "Data tidak ditemukan.";
    }
} else {
    $pesan = "ID tidak ditemukan.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hapus Menu</title>
    <style>
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: <?= $berhasil ? '#4CAF50' : '#f44336' ?>;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            opacity: 0;
            animation: fadeInOut 4s forwards;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-20px); }
            10%, 90% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-20px); }
        }
    </style>
</head>
<body>
    <div class="toast"><?= $pesan ?></div>
    <script>
        setTimeout(() => {
            window.location.href = 'menu.php';
        }, 4000);
    </script>
</body>
</html>
