<?php
require '../../conn.php';
session_start();
if (!isset($_SESSION['active']) || $_SESSION['active']['role'] !== 'user') {
    header("location: login.php");
    exit;
}
$userId = $_SESSION['active']['id'];
$getCart = mysqli_query($conn, "
    SELECT c.product_id, c.quantity, p.price
    FROM cart c
    JOIN products p ON p.id = c.product_id
    WHERE c.user_id = $userId
");
$cartItems = mysqli_fetch_all($getCart, MYSQLI_ASSOC);
if (count($cartItems) === 0) {
    echo "<p style='padding:20px'>Your cart is empty.</p>";
    exit;
}
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
$insertOrder = mysqli_query($conn, "
    INSERT INTO orders (user_id, total_price, status) VALUES ($userId, $total, 'pending')
");
if ($insertOrder) {
    $orderId = mysqli_insert_id($conn);
    foreach ($cartItems as $item) {
        mysqli_query($conn, "
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES ($orderId, {$item['product_id']}, {$item['quantity']}, {$item['price']})
        ");
        mysqli_query($conn, "
            UPDATE products SET stock_quantity = stock_quantity - {$item['quantity']}
            WHERE id = {$item['product_id']}
        ");
    }
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $userId");

    echo "
    <div style='padding:20px; background:#dff0d8;'>
        Order #$orderId placed successfully!<br>
        <a href='orders.php'>View your orders</a>
    </div>
    ";
} else {
    echo "<p>Something went wrong while placing the order.</p>";
}
