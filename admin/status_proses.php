<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status_proses'];

    // Ambil data transaksi berdasarkan ID
    $query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id = $id");
    $data = mysqli_fetch_assoc($query);

    if (!$data) {
        echo "<script>alert('Data tidak ditemukan.'); window.location.href='admin_transaksi.php';</script>";
        exit;
    }

    if ($status === 'Selesai') {
        // ✅ JIKA SELESAI: Masukkan ke laporan dengan status DITERIMA
        
        $kode   = $data['kode_order'];
        $nama   = mysqli_real_escape_string($koneksi, $data['nama']);
        $meja   = mysqli_real_escape_string($koneksi, $data['no_meja']);
        $menu   = mysqli_real_escape_string($koneksi, $data['menu']);
        $total  = $data['subtotal'];
        $metode = mysqli_real_escape_string($koneksi, $data['metode']);
        $waktu  = date("Y-m-d H:i:s");

        // Insert ke tabel laporan dengan status DITERIMA
        $query_insert = "INSERT INTO laporan 
            (kode_order, nama, no_meja, menu, subtotal, metode, status, waktu_selesai)
            VALUES 
            ('$kode', '$nama', '$meja', '$menu', $total, '$metode', 'Diterima', '$waktu')";

        $insert = mysqli_query($koneksi, $query_insert);

        if ($insert) {
            // Update status jadi Selesai
            mysqli_query($koneksi, "UPDATE transaksi SET status_proses = 'Selesai' WHERE id = $id");
        } else {
            echo "<script>alert('Gagal memindahkan ke laporan: " . mysqli_error($koneksi) . "'); window.location.href='admin_transaksi.php';</script>";
            exit;
        }
    } else {
        // Jika belum selesai, update status proses biasa
        mysqli_query($koneksi, "UPDATE transaksi SET status_proses = '$status' WHERE id = $id");
    }

    header("Location: admin_transaksi.php");
    exit;
}
?>