<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['daftar'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password_plain = $_POST['password'];
    $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

    $cek_email = $koneksi->prepare("SELECT * FROM user WHERE email = ?");
    $cek_email->bind_param("s", $email);
    $cek_email->execute();
    $result_email = $cek_email->get_result();

    if ($result_email->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar. Gunakan email lain.'); window.location='daftar.php';</script>";
        exit;
    }

    $stmt = $koneksi->prepare("INSERT INTO user (nama, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $email, $password_hash);

    if ($stmt->execute()) {
        echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat mendaftar.'); window.location='daftar.php';</script>";
    }
}
?>
