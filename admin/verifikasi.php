<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = $_POST['id'];
    $aksi = $_POST['aksi'];

    if (empty($id) || empty($aksi)) {
        echo "<script>alert('Data tidak lengkap'); window.location.href='admin_verifikasi.php';</script>";
        exit;
    }

    // Ambil data transaksi
    $query_transaksi = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id = $id");
    $transaksi = mysqli_fetch_assoc($query_transaksi);

    if (!$transaksi) {
        echo "<script>alert('Transaksi tidak ditemukan'); window.location.href='admin_verifikasi.php';</script>";
        exit;
    }

    if ($aksi == 'Diterima') {
        // ✅ JIKA DITERIMA: Update status jadi Diproses
        $query = "UPDATE transaksi SET status_bukti = 'Diterima', status_proses = 'Diproses' WHERE id = $id";
        mysqli_query($koneksi, $query);
        
        echo "<script>alert('Pembayaran berhasil disetujui'); window.location.href='admin_verifikasi.php';</script>";
    } 
    elseif ($aksi == 'Ditolak') {
        // ✅ JIKA DITOLAK: Langsung masukkan ke laporan
        
        $kode   = $transaksi['kode_order'];
        $nama   = mysqli_real_escape_string($koneksi, $transaksi['nama']);
        $meja   = mysqli_real_escape_string($koneksi, $transaksi['no_meja']);
        $menu   = mysqli_real_escape_string($koneksi, $transaksi['menu']);
        $total  = $transaksi['subtotal'];
        $metode = mysqli_real_escape_string($koneksi, $transaksi['metode']);
        $waktu  = date("Y-m-d H:i:s");

        // Insert ke tabel laporan dengan status DITOLAK
        $insert_laporan = "INSERT INTO laporan 
            (kode_order, nama, no_meja, menu, subtotal, metode, status, waktu_selesai)
            VALUES 
            ('$kode', '$nama', '$meja', '$menu', $total, '$metode', 'Ditolak', '$waktu')";
        
        if (mysqli_query($koneksi, $insert_laporan)) {
            
            // ✅ Kembalikan stok menu yang ditolak
            $menu_string = $transaksi['menu'];
            $menu_items = explode(', ', $menu_string);
            
            foreach ($menu_items as $item) {
                // Parse format: "Nasi Goreng (2x)"
                $pos = strrpos($item, '(');
                if ($pos !== false) {
                    $nama_menu = trim(substr($item, 0, $pos));
                    $quantity_part = substr($item, $pos);
                    $qty_dipesan = (int)$quantity_part;
                    
                    // Cari id_menu berdasarkan nama
                    $nama_menu_escaped = mysqli_real_escape_string($koneksi, $nama_menu);
                    $query_cari_menu = mysqli_query($koneksi, "SELECT id_menu FROM menu WHERE nama = '$nama_menu_escaped'");
                    
                    if (mysqli_num_rows($query_cari_menu) > 0) {
                        $data_menu = mysqli_fetch_assoc($query_cari_menu);
                        $id_menu = $data_menu['id_menu'];
                        
                        // Kembalikan stok
                        mysqli_query($koneksi, "UPDATE menu SET stok = stok + $qty_dipesan WHERE id_menu = $id_menu");
                    }
                }
            }

            // ✅ Update status transaksi jadi Ditolak (jangan dihapus!)
            $query = "UPDATE transaksi SET status_bukti = 'Ditolak', status_proses = 'Ditolak' WHERE id = $id";
            mysqli_query($koneksi, $query);

            echo "<script>alert('Pembayaran ditolak. Data telah dipindahkan ke laporan dan stok dikembalikan.'); window.location.href='admin_verifikasi.php';</script>";
        } else {
            echo "<script>alert('Gagal memindahkan ke laporan: " . mysqli_error($koneksi) . "'); window.location.href='admin_verifikasi.php';</script>";
        }
    }
}
?>