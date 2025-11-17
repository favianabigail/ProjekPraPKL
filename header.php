<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$total_items = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_items += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
</body>
</html>

<header id="Home">
    <style>
/* Navbar dasar */
nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 10px;
    background-color: #222;
    color: white;
}

nav .logo img {
    height: 70;
}

nav ul {
    list-style: none;
    display: flex;
    gap: 20px;
    margin: 0;
    padding: 0;
}

nav ul li a {
    text-decoration: none;
    color: white;
    font-size: 14px;
    font-weight: 500;
}


.cart {
    display: flex;
    align-items: center;
    gap: 18px; 
    position: relative;
}

.nav-icon {
    color: white;
    font-size: 18px;
    text-decoration: none;
    position: relative;
}

.nav-icon:hover {
    color: #f57c00;
}

/* Badge jumlah keranjang */
.cart-count {
    position: absolute;
    top: -6px;
    right: -10px;
    background: red;
    color: white;
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 50%;
    font-weight: bold;
}

/* Login text */
.login-text {
    font-size: 14px;
    font-weight: 500;
    color: #f57c00;
    text-decoration: none;
}

    </style>
    <nav>
        <div class="logo">
        <img src="img/logo2.png" alt="logo">
    </div>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="snack.php">Order</a></li>
        </ul>

    <?php if (!isset($hide_cart) || !$hide_cart): ?>
    <div class="cart">
        <?php if (isset($_SESSION['email'])): ?>
            <a href="riwayat_pesanan.php" class="nav-icon">
                <i class="fa-regular fa-clipboard"></i>
            </a>
            <a href="logout.php" class="nav-icon">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        <?php else: ?>
            <a href="login.php" class="login-text">Login</a>
        <?php endif; ?>

        <a href="pemesanan.php" class="nav-icon">
            <i class="fa-solid fa-cart-shopping"></i>
            <?php if ($total_items > 0): ?>
                <span class="cart-count"><?= $total_items ?></span>
            <?php endif; ?>
        </a>
    </div>
        <?php endif; ?>
    </nav>

</header>