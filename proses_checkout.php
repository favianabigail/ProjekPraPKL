<?php
session_start();
include "koneksi.php";

// Cek stok: pastikan tidak ada menu dengan stok = 0
foreach ($_SESSION['cart'] as $item) {
    if (isset($item['id_menu'])) {
        $id_menu = (int)$item['id_menu'];
        $cek_stok = mysqli_query($koneksi, "SELECT stok FROM menu WHERE id_menu = $id_menu");
        $stok_data = mysqli_fetch_assoc($cek_stok);

        if ($stok_data && $stok_data['stok'] == 0) {
            echo "<script>alert('Menu \"{$item['nama']}\" sudah habis dan tidak bisa dipesan.'); window.location.href='pemesanan.php';</script>";
            exit;
        }
    }
}

// Jika form checkout dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nomor_meja']) && !empty($_SESSION['cart'])) {
        
        // Simpan nomor meja ke session
        $_SESSION['meja'] = $_POST['nomor_meja'];

        // Hitung total belanja
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['harga'] * $item['quantity'];
        }
        $_SESSION['total'] = $total;

        $menu = [];
        $totalQuantity = 0;
        foreach ($_SESSION['cart'] as $item) {
            $menu[] = $item['nama'] . " (" . $item['quantity'] . "x)";
            $totalQuantity += $item['quantity'];
        }
        $menu = implode(', ', $menu);
        $_SESSION['menu'] = $menu;

        
        $_SESSION['kode_order'] = strtoupper(substr(md5(time()), 0, 8));

        $kode_order = $_SESSION['kode_order'];
        $id_user    = $_SESSION['id_user'];

// Insert transaksi utama (jika belum ada)
            $check = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE kode_order='$kode_order'");
            if (mysqli_num_rows($check) == 0) {
           $id_user = $_SESSION['id_user'];

           // ✅ PERBAIKAN: Isi kolom menu dan quantity dengan data yang benar
        mysqli_query($koneksi, "INSERT INTO transaksi 
            (kode_order, id_user, no_meja, nama, menu, quantity, subtotal, metode, status_proses, tanggal, bukti_pembayaran, status_bukti)
            VALUES 
            ('$kode_order', $id_user, '{$_SESSION['meja']}', '{$_SESSION['nama']}', '$menu', $totalQuantity, $total, '-', 'Pending', NOW(), '', 'Menunggu')");
}



        // Insert detail transaksi
        foreach ($_SESSION['cart'] as $item) {
            $id_menu = $item['id_menu'];
            $qty     = $item['quantity'];
            $harga   = $item['harga'];
            $subtotal = $harga * $qty;

            // mysqli_query($koneksi, "INSERT INTO transaksi_detail (kode_order, menu, jumlah, harga, subtotal) 
            //                         VALUES ('$kode_order', '{$item['nama']}', '$qty', '$harga', '$subtotal')");
        }

        // Lanjut ke transaksi.php
        header("Location: transaksi.php");
        exit;
    } else {
        echo "Nomor meja atau cart kosong!";
        exit;
    }
} else {
    header("Location: pemesanan.php");
    exit;
}
?>
