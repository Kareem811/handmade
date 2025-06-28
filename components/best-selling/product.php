<?php
require "../../conn.php";
session_start();
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // تأمين id
    $query = "
        SELECT products.id, products.name, products.price, products.description, products.stock_quantity, 
               GROUP_CONCAT(product_images.image_path) AS images 
        FROM products 
        LEFT JOIN product_images ON products.id = product_images.product_id 
        WHERE products.id = $id 
        GROUP BY products.id
    ";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../../css/main.css">
        <link rel="stylesheet" href="../../css/nav.css">
        <link rel="stylesheet" href="../../css/products.css">
        <title>Single Product</title>
    </head>

    <body>
        <?php include '../../nav.php'; ?>
        <section id="single-product-container">
            <h1>Product Details</h1>
            <div class="products">
                <?php if ($product) {
                    $arr = explode(',', $product['images']);
                    if (!empty($arr[0])) { ?>
                        <img src="../../admin/<?= $arr[0]; ?>" alt="<?= $product['name'] ?>">
                    <?php } ?>
                    <div class="product">
                        <h3><?= $product['name']; ?></h3>
                        <span>Price: $<?= $product['price']; ?></span>
                        <span>Available Stock: <?= $product['stock_quantity']; ?></span>
                        <p><?= $product['description']; ?></p>
                    </div>
                    <?php
                    if (count($arr) > 0) { ?>
                        <div class="images">
                            <h4>Product Images</h4>
                            <?php
                            foreach ($arr as $src) {
                            ?>
                                <img src="../../admin/<?= $src ?>" alt="">
                            <?php
                            }
                            ?>
                        </div>
                    <?php }
                    ?>

                <?php } else { ?>
                    <p style="color:red; font-size:20px;">No data for this product.</p>
                <?php } ?>
            </div>
        </section>
    </body>

    </html>
<?php
} else {
    echo "<p style='color:red; font-size:20px;'>No data for this product.</p>";
}
?>