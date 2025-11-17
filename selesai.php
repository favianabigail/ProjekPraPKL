<?php
session_start();
include "koneksi.php";

if (isset($_GET['meja'])) {
    $meja = $koneksi->real_escape_string($_GET['meja']);

    date_default_timezone_set('Asia/Jakarta');
    $tanggal = date("Y-m-d");
    $jam = date("H:i:s");

    
    $kode_order = strtoupper(substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, 7));
    $metode = "Cash"; 

    
    $result = $koneksi->query("SELECT * FROM pelanggan_selesai WHERE meja = '$meja'");

    $nama = "";
    $daftar_menu = [];
    $subtotal = 0;

    while ($row = $result->fetch_assoc()) {
        $nama = $row['nama']; 
        $sub = $row['subtotal'];
        $menu = $row['menu'];
        $qty = $row['qty'];
        $daftar_menu[] = "$menu ($qty)";
        $subtotal += $sub;
    }

    $gabungan_menu = implode(", ", $daftar_menu);

    $koneksi->query("INSERT INTO transaksi (kode_order, nama, no_meja, menu, subtotal, metode, tanggal, jam)
                     VALUES ('$kode_order', '$nama', '$meja', '$gabungan_menu', $subtotal, '$metode', '$tanggal', '$jam')");

    $koneksi->query("DELETE FROM pelanggan_selesai WHERE meja = '$meja'");

    header("Location: antrean.php");
    exit;
}
?>
