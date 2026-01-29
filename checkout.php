<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: cart.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $items = json_encode($cart);
    $total = 0;
    foreach ($cart as $id => $qty) {
        $query = "SELECT price FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $price = $stmt->get_result()->fetch_assoc()['price'];
        $total += $price * $qty;
    }
    $shipping_address = $_POST['shipping_address'];
    $billing_address = $_POST['billing_address'];
    
    $order_stmt = $conn->prepare("INSERT INTO orders (user_id, items, total, shipping_address, billing_address) VALUES (?, ?, ?, ?, ?)");
    $order_stmt->bind_param("isdss", $user_id, $items, $total, $shipping_address, $billing_address);
    $order_stmt->execute();
    $order_id = $conn->insert_id;