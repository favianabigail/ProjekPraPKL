<!-- <?php
include "koneksi.php";

if (isset($_GET['meja'])) {
    $meja = $_GET['meja'];

    
    $result = $koneksi->query("SELECT * FROM pelanggan_aktif WHERE meja = '$meja'");
    while ($row = $result->fetch_assoc()) {
        $nama = $row['nama'];
        $menu = $row['menu'];
        $qty = $row['quantity'];
        $subtotal = $row['subtotal'];

        
        $koneksi->query("INSERT INTO pelanggan_selesai (meja, nama, menu, quantity, subtotal) 
                         VALUES ('$meja', '$nama', '$menu', $qty, $subtotal)");
    }

    
    $koneksi->query("DELETE FROM pelanggan_aktif WHERE meja = '$meja'");

    header("Location: antrean.php");
    exit;
}
?> -->
