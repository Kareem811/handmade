<?php
require '../../conn.php';
session_start();
if (!isset($_SESSION['active']) || $_SESSION['active']['role'] !== 'user') {
    header("location: ../../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("location: orders.php");
    exit;
}

$orderId = intval($_GET['id']);
$userId = $_SESSION['active']['id'];

$order = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM orders WHERE id=$orderId AND user_id=$userId
"));

if (!$order) {
    echo "Order not found.";
    exit;
}

$items = mysqli_query($conn, "
    SELECT oi.*, p.name
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = $orderId
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <style>
        #order-details {
            width: 90%;
            min-height: 100vh;
            margin: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .order-item {
            width: 80%;
            background: #f0f0f0;
            margin: 10px;
            padding: 10px;
        }
    </style>
</head>

<body>
    <?php include '../../nav.php'; ?>
    <section id="order-details">
        <h1>Order #<?= $order['id'] ?> Details</h1>
        <?php
        if (mysqli_num_rows($items) > 0) {
            while ($item = mysqli_fetch_assoc($items)) { ?>
                <div class="order-item">
                    <h3><?= $item['name'] ?></h3>
                    <p>Quantity: <?= $item['quantity'] ?></p>
                    <p>Price per unit: $<?= $item['price'] ?></p>
                    <p>Total: $<?= $item['quantity'] * $item['price'] ?></p>
                </div>
        <?php }
        } else {
            echo "<p>No items for this order.</p>";
        }
        ?>
        <a href="/handmade/components/orders/orders.php">Back to orders</a>
    </section>
</body>

</html>