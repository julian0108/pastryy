<?php
include 'config.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Add to cart
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!isset($cart[$id])) $cart[$id] = 0;
    $cart[$id]++;
    $_SESSION['cart'] = $cart;
    header("Location: cart.php");
}

// Remove from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $id = $_GET['id'];
    unset($cart[$id]);
    $_SESSION['cart'] = $cart;
    header("Location: cart.php");
}

include 'includes/header.php';
?>

<div class="container mt-5">
    <h2>Your Cart</h2>
    <?php if (empty($cart)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($cart as $id => $qty) {
                    $query = "SELECT * FROM products WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $product = $stmt->get_result()->fetch_assoc();
                    $subtotal = $product['price'] * $qty;
                    $total += $subtotal;
                    echo "<tr>
                        <td>{$product['name']}</td>
                        <td>$qty</td>
                        <td>\${$product['price']}</td>
                        <td>\$$subtotal</td>
                        <td><a href='cart.php?action=remove&id=$id' class='btn btn-danger'>Remove</a></td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        <p>Total: $<?php echo $total; ?></p>
        <a href="checkout.php" class="btn btn-primary">Checkout</a>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>