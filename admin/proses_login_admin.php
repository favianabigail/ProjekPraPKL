<?php
include '../koneksi.php';
session_start();

$nama = $_POST['nama'];
$password = $_POST['password'];

$stmt = $koneksi->prepare("SELECT * FROM admin WHERE nama = ? AND password = ?");
$stmt->bind_param("ss", $nama, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $_SESSION['id_admin'] = $row['id_admin'];
    $_SESSION['nama'] = $row['nama'];
    header("Location: admin_verifikasi.php");
    exit;
}

header("Location: login_admin.php?error=1");
exit;
?>
