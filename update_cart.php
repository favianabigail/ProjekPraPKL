<?php
session_start();

if (isset($_POST['action']) && isset($_POST['nama'])) {
    $nama = $_POST['nama'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['nama'] == $nama) {
                if ($action == 'increase') {
                    $_SESSION['cart'][$key]['quantity']++;
                } elseif ($action == 'decrease' && $_SESSION['cart'][$key]['quantity'] > 1) {
                    $_SESSION['cart'][$key]['quantity']--;
                } elseif ($action == 'delete') {
                    unset($_SESSION['cart'][$key]);
                }
                break;
            }
        }
        $_SESSION['cart'] = array_values($_SESSION['cart']); // reset index
    }
}

header("Location: pemesanan.php");
exit;
?>
