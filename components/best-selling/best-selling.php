<div id="best-selling-container">
    <h1>Best Selling Products</h1>
    <?php
    $selectQuery = "
        SELECT products.id, products.name ,products.price, products.description, products.stock_quantity, 
        GROUP_CONCAT(product_images.image_path) as images 
        FROM products 
        LEFT JOIN product_images ON products.id = product_images.product_id 
        GROUP BY products.id
    ";
    $result = mysqli_query($conn, $selectQuery);
    $allProducts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $productsWithImages = array_filter($allProducts, function ($product) {
        return !empty($product['images']);
    });
    $topProducts = array_slice($productsWithImages, 0, 3);
    ?>
    <div class="best-selling">
        <?php foreach ($topProducts as $product): ?>
            <a href="product.php?id=<?php echo $product['id']; ?>" class="product">
                <?php
                $imagesArr = explode(',', $product['images']);
                $firstImage = $imagesArr[0];
                ?>
                <img src="admin/<?= $firstImage ?>" alt="<?= $product['name'] ?>">
                <h3><?= $product['name'] ?></h3>
                <p><?= $product['price'] ?> EGP</p>
            </a>
        <?php endforeach; ?>
    </div>
    <a class="explore-btn" href="products.php">Explore</a>
</div>