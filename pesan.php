<?php
session_start();
include "koneksi.php";
include "header.php";

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit;
}

$total_items = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_items += $item['quantity'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
   $id_menu = $_POST['id_menu'];
$nama = $_POST['nama'];
$harga = $_POST['harga'];
$gambar = $_POST['gambar'];
$quantity = (int)$_POST['quantity'];

// Ambil stok dari database
$sql_stok = "SELECT stok FROM menu WHERE id_menu = '$id_menu'";
$result_stok = mysqli_query($koneksi, $sql_stok);
$data_stok = mysqli_fetch_assoc($result_stok);
$stok_tersedia = (int)$data_stok['stok'];

// Hitung quantity total di cart saat ini untuk menu ini
$existing_quantity = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if ($item['id_menu'] == $id_menu) {
            $existing_quantity = $item['quantity'];
            break;
        }
    }
}

if ($existing_quantity + $quantity > $stok_tersedia) {
    echo "<script>alert('Stok tidak mencukupi. Sisa stok: $stok_tersedia'); window.location.href='pesan.php?id_menu=$id_menu';</script>";
    exit;
}


    $item = [
        'id_menu' => $id_menu,
        'nama' => $nama,
        'harga' => $harga,
        'gambar' => $gambar,
        'quantity' => $quantity
    ];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id_menu'] == $id_menu) {
            $cart_item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    unset($cart_item); 

    if (!$found) {
        $_SESSION['cart'][] = $item;
    }

    header("Location: pesan.php?id_menu=$id_menu&added=1");
    exit;
}


$id = $_GET['id_menu'];
$sql = "SELECT * FROM menu WHERE id_menu = '$id'";
$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pesan Menu</title>
    <link rel="stylesheet" href="pesan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .out-of-stock-message {
            color: #e74c3c;
            font-weight: bold;
            margin: 10px 0;
            padding: 5px;
            background-color: #ffe6e6;
            border: 1px solid #e74c3c;
            border-radius: 4px;
            text-align: center;
            display: none;
        }
        
        .disabled-button {
            background-color: #cccccc !important;
            cursor: not-allowed !important;
            opacity: 0.7;
        }
        
        .stock-info {
            margin: 5px 0;
            font-weight: bold;
        }
        
        .available {
            color: #27ae60;
        }
        
        .unavailable {
            color: #e74c3c;
        }
    </style>
</head>
<body>

<?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
    
<?php endif; ?>


<div class="container">
    <div class="sidebar">
        <input type="text" placeholder="Search Menu..."><button><i class="fas fa-search"></i></button>
        <ul>
            <li><a href="snack.php">Snack</a></li>
            <li><a href="makanan.php">Makanan</a></li>
            <li><a href="minuman.php">Minuman</a></li>
            <li><a href="paketkeluarga.php">Paket Sekeluarga</a></li>
        </ul>
    </div>

    <div class="main-content">
        <?php while ($snack = mysqli_fetch_assoc($query)): 
            $stok = $snack['stok'];
            // Hitung quantity yang sudah ada di cart
            $existing_quantity_in_cart = 0;
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    if ($item['id_menu'] == $snack['id_menu']) {
                        $existing_quantity_in_cart = $item['quantity'];
                        break;
                    }
                }
            }
            $stok_tersedia = $stok - $existing_quantity_in_cart;
        ?>
            <form method="POST" action="pesan.php?id_menu=<?= $snack['id_menu'] ?>" data-stok-tersedia="<?= $stok_tersedia ?>">
                <img src="img/<?= $snack['link_gambar'] ?>" alt="<?= $snack['nama'] ?>">
                <h3><?= $snack['nama'] ?></h3>
                <p>Rp. <?= number_format($snack['harga'], 0, ',', '.') ?></p>
                
                <!-- Informasi stok -->
                <div class="stock-info <?= ($stok_tersedia <= 0) ? 'unavailable' : 'available' ?>">
                    Stok: <?= $stok_tersedia ?> tersedia
                </div>
                

                <div class="quantity">
                    <button type="button" class="decrease">-</button>
                    <span class="quantity-display">1</span>
                    <button type="button" class="increase">+</button>
                </div>

                <input type="hidden" name="id_menu" value="<?= $snack['id_menu'] ?>">
                <input type="hidden" name="nama" value="<?= $snack['nama'] ?>">
                <input type="hidden" name="harga" value="<?= $snack['harga'] ?>">
                <input type="hidden" name="gambar" value="<?= $snack['link_gambar'] ?>">
                <input type="hidden" name="quantity" class="quantity-input" value="1">

                <button type="submit" name="add_to_cart" class="add-to-cart">
                    Add To Cart
                </button>
            </form>
        <?php endwhile; ?>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("Script dimulai");
    const forms = document.querySelectorAll(".main-content form[data-stok-tersedia]");
    console.log("Jumlah form menu:", forms.length);

    forms.forEach(form => {
        const btnPlus = form.querySelector(".increase");
        const btnMinus = form.querySelector(".decrease");
        const qtyDisplay = form.querySelector(".quantity-display");
        const qtyInput = form.querySelector(".quantity-input");
        const addToCartBtn = form.querySelector(".add-to-cart");
        const stockInfo = form.querySelector(".stock-info");
        const maxStok = parseInt(form.dataset.stokTersedia);

        if (!btnPlus || !btnMinus) {
            console.warn("Tombol tidak ditemukan di form ini:", form);
            return;
        }

        let quantity = 1;

        function updateQtyDisplay() {
            qtyDisplay.textContent = quantity;
            qtyInput.value = quantity;
            if (quantity > maxStok) {
                addToCartBtn.disabled = true;
                addToCartBtn.classList.add("disabled-button");
            } else {
                addToCartBtn.disabled = false;
                addToCartBtn.classList.remove("disabled-button");
            }
        }

        btnPlus.addEventListener("click", () => {
            if (quantity < maxStok) {
                quantity++;
                updateQtyDisplay();
            } else {
                alert("Stok tidak mencukupi! Sisa stok: " + maxStok);
            }
        });

        btnMinus.addEventListener("click", () => {
            if (quantity > 1) {
                quantity--;
                updateQtyDisplay();
            }
        });

        updateQtyDisplay();
    });
});
</script>

</body>
</html>