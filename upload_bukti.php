<?php
session_start();
include "koneksi.php";

// Fungsi untuk generate kode unik
function generateKode($koneksi) {
    do {
        $kode = strtoupper(substr(str_shuffle("ABCDEFGHJKLMNPQRSTUVWXYZ123456789"), 0, 8));
        $cek  = mysqli_query($koneksi, "SELECT 1 FROM transaksi WHERE kode_order='$kode' LIMIT 1");
    } while (mysqli_num_rows($cek) > 0);
    return $kode;
}

// Ambil data dari session
$nama    = $_SESSION['nama'] ?? 'Guest';
$email   = $_SESSION['email'];
$meja    = $_SESSION['meja'] ?? '-';
$menu    = $_SESSION['menu'] ?? '';
$total   = $_SESSION['total'] ?? 0;
$metode  = $_POST['metode'] ?? 'Transfer';

// Hitung total quantity
$quantity = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $quantity += $item['quantity'];
    }
}

// Proses upload bukti pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['bukti'])) {
    $file = $_FILES['bukti'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext    = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $folder = __DIR__ . '/bukti/';
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        $filename = 'bukti_' . time() . '.' . $ext;
        $tujuan   = $folder . $filename;

        if (move_uploaded_file($file['tmp_name'], $tujuan)) {
            date_default_timezone_set('Asia/Jakarta');

            // ✅ DITAMBAHKAN: Selalu buat kode order baru setiap upload
         if (isset($_SESSION['kode_order'])) {
                $kode = $_SESSION['kode_order'];
            } else {
                $kode = generateKode($koneksi);
                $_SESSION['kode_order'] = $kode;
            }

            // ✅ Cek apakah transaksi dengan kode_order ini sudah ada
            $cek = mysqli_query($koneksi, "SELECT 1 FROM transaksi WHERE kode_order='$kode' LIMIT 1");

            if (mysqli_num_rows($cek) > 0) {
                // Kalau sudah ada → Update transaksi lama
                $id_user = $_SESSION['id_user'] ?? 0;

                $query = "UPDATE transaksi SET 
                            bukti_pembayaran='$filename',
                            metode='$metode',
                            status_bukti='Menunggu',
                            status_proses='Diproses',
                            id_user=$id_user
                        WHERE kode_order='$kode'";
            } else {
                // Kalau belum ada → Insert baru
                $id_user = $_SESSION['id_user'] ?? 0;

                $query = "INSERT INTO transaksi 
                            (kode_order, id_user, nama, no_meja, menu, quantity, subtotal, metode, bukti_pembayaran, status_bukti, status_proses, tanggal)
                        VALUES 
                            ('$kode', $id_user, '$nama', '$meja', '$menu', $quantity, $total, '$metode', '$filename', 'Menunggu', 'Diproses', NOW())";
            }

            // Kurangi stok tiap item
            foreach ($_SESSION['cart'] as $item) {
                $id_menu = (int)($item['id_menu'] ?? 0);
                $qty     = (int)($item['quantity'] ?? 0);
                if ($id_menu > 0 && $qty > 0) {
                    $cek = mysqli_query($koneksi, "SELECT stok FROM menu WHERE id_menu = $id_menu");
                    $data = mysqli_fetch_assoc($cek);
                    if ($data && $data['stok'] >= $qty) {
                        mysqli_query($koneksi, "UPDATE menu SET stok = stok - $qty WHERE id_menu = $id_menu");
                    } else {
                        echo "<script>alert('Stok tidak cukup untuk menu: " . htmlspecialchars($item['nama']) . "'); window.location.href='pemesanan.php';</script>";
                        exit;
                    }
                }
            }

            if (mysqli_query($koneksi, $query)) {
                // Simpan detail transaksi (hanya jika belum ada)
                $cek_detail = mysqli_query($koneksi, "SELECT 1 FROM transaksi_detail WHERE kode_order='$kode' LIMIT 1");
                if (mysqli_num_rows($cek_detail) == 0) {
                    foreach ($_SESSION['cart'] as $item) {
                        $nama_menu = mysqli_real_escape_string($koneksi, $item['nama']);
                        $qty       = (int)($item['quantity'] ?? 0);
                        $harga     = (int)($item['harga'] ?? 0);
                        $subtotal  = $qty * $harga;
                        mysqli_query($koneksi, "INSERT INTO transaksi_detail 
                            (kode_order, menu, jumlah, harga, subtotal) 
                            VALUES 
                            ('$kode', '$nama_menu', $qty, $harga, $subtotal)");
                    }
                }

                // ✅ DITAMBAHKAN: Reset session supaya pesanan berikutnya pakai kode baru
                unset($_SESSION['cart'], $_SESSION['total'], $_SESSION['menu']);

                echo "<script>alert('Transaksi berhasil!');window.location.href='pembayaran.php';</script>";
                exit;
            } else {
                $error = "Gagal menyimpan transaksi: " . mysqli_error($koneksi);
            }
        } else {
            $error = "Gagal upload file ke server.";
        }
    } else {
        $error = "Upload file gagal.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Bukti</title>
    <link rel="stylesheet" href="upload_bukti.css">
</head>
<body>
<div class="container">
    <div class="byr">
        <h2>Upload Bukti Pembayaran</h2>
        <p>Metode: <strong><?= htmlspecialchars($metode) ?></strong></p>

        <?php if (!empty($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="metode" value="<?= htmlspecialchars($metode) ?>">
            <input type="file" name="bukti" accept="image/*" required><br><br>
            <button type="submit">Kirim Bukti</button>
        </form>
    </div>
</div>
</body>
</html>
