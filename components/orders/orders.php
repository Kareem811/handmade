<?php
require '../../conn.php';
if (!isset($_SESSION['active']) || $_SESSION['active']['role'] !== 'user') {
    header("location: ../../login.php");
    exit;
}

$userId = $_SESSION['active']['id'];
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $userId ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <style>
        .order {
            background: #f9f9f9;
            margin: 20px auto;
            max-width: 800px;
            border: 1px solid #ddd;
            padding: 15px;
        }
    </style>
</head>

<body>
    <?php include '../../nav.php'; ?>
    <section id="orders-container">
        <h1>My Orders</h1>
        <?php if (mysqli_num_rows($orders) > 0) {
            while ($order = mysqli_fetch_assoc($orders)) { ?>
                <div class="order">
                    <h3>Order #<?= $order['id'] ?> (<?= $order['status'] ?>)</h3>
                    <p>Total: $<?= $order['total_price'] ?></p>
                    <p>Date: <?= $order['created_at'] ?></p>
                    <a href="/handmade/components/orders/order-details.php?id=<?= $order['id'] ?>">View Details</a>
                </div>
        <?php }
        } else {
            echo "<p>You have no orders yet.</p>";
        } ?>
    </section>
</body>

</html>