<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = $_POST['id'];
    $nama    = $_POST['nama'];
    $meja    = $_POST['meja'];
    $menu    = $_POST['menu'];
    $total   = $_POST['total'];
    $bayar   = $_POST['bayar'];
    $kembali = $bayar - $total;
    $metode  = "Cash";
    $kode    = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 8));
    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d");
    $jam     = date("H:i:s");

    // 1. Simpan ke tabel transaksi
    $insert = mysqli_query($koneksi, "INSERT INTO transaksi 
        (kode_order, nama, no_meja, menu, subtotal, bayar, kembalian, metode, tanggal, jam) 
        VALUES 
        ('$kode', '$nama', '$meja', '$menu', '$total', '$bayar', '$kembali', '$metode', '$tanggal', '$jam')");

    if ($insert) {
        // 2. Hapus dari tabel pesanan_cash
        mysqli_query($koneksi, "DELETE FROM pesanan_cash WHERE id = '$id'");

        // 3. Redirect ke halaman sukses
        header("Location: pembayaran_sukses.php?kode=$kode");
        exit;
    } else {
        echo "Gagal memproses transaksi: " . mysqli_error($koneksi);
    }
} else {
    echo "Akses tidak sah.";
}
