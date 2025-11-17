<?php
session_start();
include 'koneksi.php'; // pastikan file ini ada dan koneksi ke DB benar
$hide_cart = true;
$hide_dropdown = true;
include 'header.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan</title>
    <link rel="stylesheet" href="pemesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        .table-number button.active {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="menu">
        <div class="header">
            <h2>Menu Item</h2>
            <div style="display: flex; justify-content: space-between; width: 300px;">
                <span>Quantity</span>
                <span>Sub Total</span>
            </div>
        </div>

        <?php if (empty($cart)): ?>
            <p>Keranjang kosong.</p>
        <?php else: ?>
            <?php 
            foreach ($cart as $item): 
                // Ambil stok dari tabel menu berdasarkan nama
                $namaMenu = mysqli_real_escape_string($koneksi, $item['nama']);
                $query = mysqli_query($koneksi, "SELECT stok FROM menu WHERE nama = '$namaMenu' LIMIT 1");
                $row = mysqli_fetch_assoc($query);
                $stok_tersedia = $row ? (int)$row['stok'] : 0;

                $subTotal = $item['harga'] * $item['quantity'];
                $total += $subTotal;
            ?>
            <div class="cart-item">
                <img src="img/<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama']) ?>" class="image">
                <div class="item-name"><?= htmlspecialchars($item['nama']) ?></div>

                <form action="update_cart.php" method="post" class="quantity-form" 
                    data-stok="<?= $stok_tersedia ?>" 
                    data-quantity="<?= $item['quantity'] ?>">
                    <input type="hidden" name="nama" value="<?= htmlspecialchars($item['nama']) ?>">
                    <button type="submit" name="action" value="decrease">-</button>
                    <span class="quantity"><?= $item['quantity'] ?></span>
                    <button type="submit" name="action" value="increase" class="btn-plus">+</button>
                </form>

                <div class="subtotal">Rp <?= number_format($subTotal, 0, ',', '.') ?></div>
                <form action="update_cart.php" method="post">
                    <input type="hidden" name="nama" value="<?= htmlspecialchars($item['nama']) ?>">
                    <button type="submit" name="action" value="delete" class="btn-delete">Hapus</button>
                </form>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="summary">
        <div class="total">
            <h2>Total*</h2>
            <p>Rp <?= number_format($total, 0, ',', '.') ?></p>
        </div>

        <div class="table-number">
            <h2>Nomor Meja</h2>
            <div>
                <button type="button">1</button>
                <button type="button">2</button>
                <button type="button">3</button>
                <button type="button">4</button>
                <button type="button">5</button>
                <button type="button">6</button>
            </div>
        </div>

        <?php if (!empty($cart)): ?>
        <div class="checkout-section">
            <form action="proses_checkout.php" method="post" id="checkoutForm">
                <input type="hidden" name="nomor_meja" id="nomor_meja" required>
                <button type="submit" name="checkout" class="btn-checkout" onclick="return validateTableSelection()">Checkout</button>
            </form>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
    // ============ PILIH NOMOR MEJA ============
    const mejaButtons = document.querySelectorAll('.table-number button');
    const nomorMejaInput = document.getElementById('nomor_meja');

    mejaButtons.forEach(button => {
        button.addEventListener('click', () => {
            mejaButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            nomorMejaInput.value = button.textContent;
        });
    });

    function validateTableSelection() {
        if (!nomorMejaInput.value) {
            alert('Silakan pilih nomor meja terlebih dahulu!');
            return false;
        }
        return true;
    }

    // ============ NONAKTIFKAN TOMBOL + JIKA STOK HABIS ============
    document.addEventListener("DOMContentLoaded", function() {
        const forms = document.querySelectorAll('.quantity-form');

        forms.forEach(form => {
            const plusBtn = form.querySelector('.btn-plus');
            const stokTersedia = parseInt(form.dataset.stok);
            const quantity = parseInt(form.dataset.quantity);

            if (stokTersedia <= 0) {
                plusBtn.disabled = true;
                plusBtn.style.opacity = "0.6";
                plusBtn.style.cursor = "not-allowed";
                return;
            }

            if (quantity >= stokTersedia) {
                plusBtn.disabled = true;
                plusBtn.style.opacity = "0.6";
                plusBtn.style.cursor = "not-allowed";
            }
        });
    });
</script>

</body>
</html>
