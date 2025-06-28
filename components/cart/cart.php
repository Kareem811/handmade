<?php
require '../../conn.php';
session_start();
if (!isset($_SESSION['active']) || $_SESSION['active']['role'] !== 'user') {
    header("location: /handmade/login.php");
    exit;
}

$userId = $_SESSION['active']['id'];
$cartItems = mysqli_query($conn, "
    SELECT p.id, p.name, p.price, c.quantity, GROUP_CONCAT(pi.image_path) as images
    FROM cart c
    JOIN products p ON p.id = c.product_id
    LEFT JOIN product_images pi ON pi.product_id = p.id
    WHERE c.user_id = $userId
    GROUP BY p.id
");

$items = mysqli_fetch_all($cartItems, MYSQLI_ASSOC);
if (isset($_POST['remove'])) {
    $pid = intval($_POST['pid']);
    mysqli_query($conn, "DELETE FROM cart WHERE product_id = $pid AND user_id = $userId");
    header("location: cart.php");
    exit;
}
if (isset($_POST['clear'])) {
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = $userId");
    header("location: cart.php");
    exit;
}
if (isset($_POST['checkout'])) {
    if (count($items) > 0) {
        $grandTotal = 0;
        foreach ($items as $item) {
            $grandTotal += $item['price'] * $item['quantity'];
        }

        $orderQuery = "
            INSERT INTO orders (user_id, total_price, status, created_at)
            VALUES ($userId, $grandTotal, 'pending', NOW())
        ";
        $orderDone = mysqli_query($conn, $orderQuery);

        if ($orderDone) {
            $orderId = mysqli_insert_id($conn);

            foreach ($items as $item) {
                mysqli_query($conn, "
                    INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES ($orderId, {$item['id']}, {$item['quantity']}, {$item['price']})
                ");
                mysqli_query($conn, "
                    UPDATE products
                    SET stock_quantity = stock_quantity - {$item['quantity']}
                    WHERE id = {$item['id']} AND stock_quantity >= {$item['quantity']}
                ");
            }
            mysqli_query($conn, "DELETE FROM cart WHERE user_id = $userId");

            header("location: /handmade/components/orders/orders.php?success=1");
            exit;
        } else {
            echo "<p style='color:red'>Something went wrong while creating order.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <style>
        #cart-container {
            width: 90%;
            padding: 40px;
            margin: 50px auto 0 auto;
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f9f9f9;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .cart-item img {
            width: 60px;
            margin-right: 15px;
        }

        .details {
            flex: 1;
        }

        .cart-total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }

        .cart-actions {
            margin-top: 20px;
            text-align: right;
        }

        .cart-actions button {
            padding: 8px 12px;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <?php include '../../nav.php'; ?>
    <section id="cart-container">
        <h1>Your Cart</h1>
        <?php
        if (count($items) > 0) {
            $grandTotal = 0;
            foreach ($items as $item) {
                $imagesArr = explode(',', $item['images']);
                $firstImage = !empty($imagesArr[0]) ? "../../admin/" . $imagesArr[0] : '../../admin/images/no-image.png';
                $total = $item['price'] * $item['quantity'];
                $grandTotal += $total;
        ?>
                <div class="cart-item">
                    <img src="<?= $firstImage ?>" alt="<?= $item['name'] ?>">
                    <div class="details">
                        <h3><?= $item['name'] ?></h3>
                        <p>Price: $<?= $item['price'] ?></p>
                        <p>Quantity: <?= $item['quantity'] ?></p>
                        <p>Total: $<?= $total ?></p>
                    </div>
                    <form method="post">
                        <input type="hidden" name="pid" value="<?= $item['id'] ?>">
                        <button name="remove">Remove</button>
                    </form>
                </div>
            <?php
            }
            ?>
            <div class="cart-total">Grand Total: $<?= $grandTotal ?></div>
            <div class="cart-actions">
                <form method="post" style="display:inline">
                    <button name="clear">Clear Cart</button>
                </form>
                <form method="post" style="display:inline">
                    <button name="checkout">Checkout</button>
                </form>
            </div>
        <?php } else {
            echo "<p>Your cart is empty.</p>";
        } ?>
    </section>
</body>

</html>