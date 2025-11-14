<<<<<<< HEAD
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
=======
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
>>>>>>> dda290f9641bc7c7d4ef822358db5a4a78609379
