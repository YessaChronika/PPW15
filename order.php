<?php
include 'config.php';

$sql = "SELECT id_menu, nama_menu, deskripsi, harga, foto FROM menu";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Menu</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="menu-section">
    <h2>Our Menu</h2>
    <div class="menu-items">
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="menu-item" data-id="<?= $row['id_menu'] ?>" data-price="<?= $row['harga'] ?>">
            <img src="<?= $row['foto'] ?>" alt="<?= $row['nama_menu'] ?>">
            <h3><?= $row['nama_menu'] ?></h3>
            <p><?= $row['deskripsi'] ?></p>
            <div class="harga">Rp <?= number_format($row['harga'], 2, ',', '.') ?></div>
            <button class="add-to-cart">+</button>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<div class="cart-container">
    <div class="button-keranjang">
        <button id="cartButton" onclick="toggleCartDropdown()">Order</button>
    </div>
    <div id="cartDropdown" class="cart-dropdown">
        <h3>Your Cart</h3>
        <div id="cartItems"></div>
        <div class="cart-total">
            <strong>Total:</strong> Rp <span id="totalAmount">0</span>
        </div>
        <form id="placeOrderForm" method="POST" action="my-reservasi.php">
            <input type="hidden" name="orderDetails" id="orderDetails">
            <button type="submit">Place Order</button>
        </form>
    </div>
</div>

<script>
let cart = [];

document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        const item = button.parentElement;
        const id = item.getAttribute('data-id');
        const price = parseFloat(item.getAttribute('data-price'));
        const name = item.querySelector('h3').innerText;

        addToCart(id, name, price);
        updateCart();
    });
});

function addToCart(id, name, price) {
    const existingItem = cart.find(item => item.id === id);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({ id, name, price, quantity: 1 });
    }
}

function updateCart() {
    const cartItemsDiv = document.getElementById('cartItems');
    const totalAmountSpan = document.getElementById('totalAmount');
    cartItemsDiv.innerHTML = '';
    let total = 0;

    cart.forEach(item => {
        total += item.price * item.quantity;
        cartItemsDiv.innerHTML += `<p>${item.name} x${item.quantity} - Rp ${item.price * item.quantity} <span onclick="removeFromCart(${item.id})" class="remove-item">✖</span></p>`;
    });

    totalAmountSpan.innerText = total.toLocaleString();
    document.getElementById('orderDetails').value = JSON.stringify(cart);
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id.toString());
    updateCart();
}

function toggleCartDropdown() {
    document.getElementById('cartDropdown').classList.toggle('show');
}
</script>
</body>
</html>
