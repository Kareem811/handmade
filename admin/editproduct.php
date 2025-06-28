<?php
require '../conn.php';
session_start();
if (isset($_SESSION)) {
    if (isset($_SESSION['active'])) {
        if ($_SESSION['active']['role'] !== "admin") {
            header("location: notfound.php");
            exit;
        } else {
            if (!isset($_GET['id'])) {
                header("location: products.php");
                exit;
            }
            $productId = $_GET['id'];
            $productQuery = mysqli_query($conn, "SELECT * FROM products WHERE id = $productId");
            $product = mysqli_fetch_assoc($productQuery);
            $imagesQuery = mysqli_query($conn, "SELECT image_path FROM product_images WHERE product_id = $productId");
            $images = [];
            while ($img = mysqli_fetch_assoc($imagesQuery)) {
                $images[] = $img['image_path'];
            }

            $errors = [];

            if (isset($_POST['updateproduct'])) {
                $productName = trim(htmlspecialchars($_POST['productname']));
                $productPrice = trim(htmlspecialchars($_POST['productprice']));
                $productDescription = trim(htmlspecialchars($_POST['productdescription']));
                $productQuantity = trim(htmlspecialchars($_POST['productquantity']));
                $imagesNew = $_FILES['images'];

                if (
                    empty($productName) || empty($productPrice)
                    || empty($productDescription) || empty($productQuantity)
                ) {
                    $errors['fields'] = "All fields are required.";
                } else {
                    $updateQuery = "
                        UPDATE products SET 
                            name = '$productName', 
                            price = '$productPrice', 
                            description = '$productDescription',
                            stock_quantity = '$productQuantity'
                        WHERE id = $productId
                    ";
                    $updateDone = mysqli_query($conn, $updateQuery);

                    $uploadedImages = 0;

                    if (!empty($imagesNew['name'][0])) {
                        for ($i = 0; $i < count($imagesNew['name']); $i++) {
                            $imgName = time() . '_' . basename($imagesNew['name'][$i]);
                            $tmp = $imagesNew['tmp_name'][$i];
                            $path = "images/" . $imgName;
                            if (move_uploaded_file($tmp, $path)) {
                                $dbPath = "images/" . $imgName;
                                $insertImage = "INSERT INTO product_images (product_id, image_path) VALUES ('$productId', '$dbPath')";
                                $imageDone = mysqli_query($conn, $insertImage);
                                if ($imageDone) {
                                    $uploadedImages++;
                                }
                            }
                        }
                    }
?>
                    <div class="success">
                        <div class="content">
                            <h3>
                                Product Updated Successfully
                                <?php if ($uploadedImages > 0) echo "(with $uploadedImages new images)"; ?>
                            </h3>
                            <a href="products.php">Confirm</a>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="css/main.css">
                <link rel="stylesheet" href="css/nav.css">
                <link rel="stylesheet" href="css/dashboard.css">
                <title>Admin - Edit Product</title>
            </head>

            <body>
                <?php include 'nav.php'; ?>
                <div id="dashboard-container">
                    <img src="../assets/images/background.webp" alt="">
                    <div class="layer">
                        <form method="post" enctype="multipart/form-data">
                            <h2>Edit Product</h2>
                            <?php if (isset($errors['fields'])) { ?>
                                <span style="color:red"><?= $errors['fields']; ?></span>
                            <?php } ?>
                            <input type="text" name="productname" value="<?= $product['name'] ?>" placeholder="Product Name">
                            <input type="text" name="productprice" value="<?= $product['price'] ?>" placeholder="Product Price">
                            <input type="text" name="productquantity" value="<?= $product['stock_quantity'] ?>" placeholder="Product Quantity">
                            <input type="file" name="images[]" multiple>
                            <textarea name="productdescription" placeholder="Product Description"><?= $product['description'] ?></textarea>
                            <input type="submit" value="Update Product" name="updateproduct">

                            <h3 style="margin-top:20px;">Current Images:</h3>
                            <div class="product-images">
                                <?php
                                if (count($images) > 0) {
                                    foreach ($images as $src) { ?>
                                        <img src="<?= $src ?>" alt="<?= $product['name'] ?>">
                                <?php }
                                } else {
                                    echo "<p>No images available for this product.</p>";
                                }
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
            </body>

            </html>
<?php
        }
    } else {
        header('location: notfound.php');
        exit;
    }
}
?>