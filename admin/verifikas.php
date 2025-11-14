<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['id_admin'])) {
    echo "<script>alert('Akses ditolak. Silakan login sebagai admin.'); window.location.href='login_admin.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_transaksi = $_POST['id'];
    $aksi = $_POST['aksi'];

    // 1. Ambil data transaksi
    $query_transaksi = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id = '$id_transaksi'");
    $transaksi = mysqli_fetch_assoc($query_transaksi);

    if ($transaksi) {
        if ($aksi == 'Diterima') {
            $update_query = "UPDATE transaksi SET status_bukti = 'Diterima', status_proses = 'Diproses' WHERE id = '$id_transaksi'";
            $message = "Pembayaran berhasil disetujui.";
        } 
        elseif ($aksi == 'Ditolak') {
            // **PENGEMBALIAN STOK - REVISI**
            $menu_string = $transaksi['menu'];
            
            // Cara alternatif: split by comma dulu, lalu parse masing-masing
            $menu_items = explode(', ', $menu_string);
            
            foreach ($menu_items as $item) {
                // Cari posisi tanda kurung
                $pos = strrpos($item, '(');
                if ($pos !== false) {
                    $nama_menu = trim(substr($item, 0, $pos));
                    $quantity_part = substr($item, $pos + 1);
                    $qty_dipesan = (int)$quantity_part;
                    
                    // Cari id_menu berdasarkan nama menu
                    $query_cari_menu = mysqli_query($koneksi, "SELECT id_menu FROM menu WHERE nama = '$nama_menu'");
                    
                    if (mysqli_num_rows($query_cari_menu) > 0) {
                        $data_menu = mysqli_fetch_assoc($query_cari_menu);
                        $id_menu = $data_menu['id_menu'];
                        
                        // Kembalikan stok
                        $query_kembalikan_stok = "UPDATE menu SET stok = stok + $qty_dipesan WHERE id_menu = $id_menu";
                        mysqli_query($koneksi, $query_kembalikan_stok);
                    }
                }
            }

            $update_query = "UPDATE transaksi SET status_bukti = 'Ditolak', status_proses = 'Ditolak' WHERE id = '$id_transaksi'";
            $message = "Pembayaran ditolak. Stok telah dikembalikan.";
        }

        if (mysqli_query($koneksi, $update_query)) {
            echo "<script>alert('$message'); window.location.href='admin_verifikasi.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($koneksi) . "'); window.location.href='admin_verifikasi.php';</script>";
        }
    }
}
?>