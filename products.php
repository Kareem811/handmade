<?php
require 'conn.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/nav.css" />
    <link rel="stylesheet" href="css/products.css" />
    <title>Products</title>
</head>

<body>
    <?php include 'nav.php'; ?>
    <section id="products-container">
        <h1>Available Collection</h1>
        <div class="products">
            <?php
            $selectQuery = "SELECT products.id, products.name ,products.price, products.description, products.stock_quantity, 
                        GROUP_CONCAT(product_images.image_path) as images 
                        FROM products 
                        LEFT JOIN product_images ON products.id = product_images.product_id 
                        GROUP BY products.id";
            $products = mysqli_fetch_all(mysqli_query($conn, $selectQuery), MYSQLI_ASSOC);

            foreach ($products as $product) {
                $arr = explode(',', $product['images']);
            ?>
                <div class="product">
                    <?php if (!empty($arr[0])) { ?>
                        <img src="admin/<?= $arr[0]; ?>" alt="<?= $product['name']; ?>" />
                    <?php } ?>
                    <h3><?= $product['name']; ?></h3>
                    <span>Price: $<?= $product['price']; ?></span>
                    <span>Available Stock: <?= $product['stock_quantity']; ?></span>
                    <div class="buttons">
                        <a href="components/best-selling/product.php?id=<?= $product['id']; ?>">See More</a>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                            <button type="submit" name="addToCart">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</body>

</html>

<?php
if (isset($_POST['addToCart'])) {
    if (!isset($_SESSION['active']) || $_SESSION['active']['role'] !== "user") {
        echo "<script>alert('Please login first to add products to your cart'); window.location='login.php';</script>";
        exit;
    }
    $user_id = $_SESSION['active']['id'];
    $product_id = intval($_POST['product_id']);
    $check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('This product is already in your cart'); window.location='products.php';</script>";
        exit;
    }

    $insert = mysqli_query($conn, "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, 1)");
    if ($insert) {
        echo "<script>alert('Product added to cart successfully'); window.location='products.php';</script>";
        exit;
    } else {
        echo "<script>alert('Something went wrong');</script>";
    }
}
?>