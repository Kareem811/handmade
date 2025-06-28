<?php
require '../conn.php';
session_start();

if (isset($_SESSION)) {
    if (isset($_SESSION['active'])) {
        if ($_SESSION['active']['role'] !== "admin") {
            header("location: notfound.php");
            exit;
        } else { ?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="css/main.css">
                <link rel="stylesheet" href="css/nav.css">
                <link rel="stylesheet" href="css/dashboard.css">
                <title>Admin - Add Product</title>
            </head>

            <body>
                <?php include 'nav.php'; ?>
                <?php
                $errors = [];

                if (isset($_POST['addproduct'])) {
                    $productName = trim(htmlspecialchars($_POST['productname']));
                    $productPrice = trim(htmlspecialchars($_POST['productprice']));
                    $productDescription = trim(htmlspecialchars($_POST['productdescription']));
                    $productQuantity = trim(htmlspecialchars($_POST['productquantity']));
                    $images = $_FILES['images'];

                    if (
                        empty($productName) || empty($productPrice)
                        || empty($productDescription) || empty($productQuantity)
                    ) {
                        $errors['fields'] = "All fields are required.";
                    } else {
                        $insertQuery = "INSERT INTO products (name, price, description, stock_quantity)
                                    VALUES ('$productName', '$productPrice', '$productDescription', '$productQuantity')";
                        $insertDone = mysqli_query($conn, $insertQuery);
                        if ($insertDone) {
                            $product_id = mysqli_insert_id($conn);
                            $uploadedImages = 0;

                            if (!empty($images['name'][0])) {
                                // images uploaded
                                for ($i = 0; $i < count($images['name']); $i++) {
                                    $imgName = time() . '_' . basename($images['name'][$i]);
                                    $tmp = $images['tmp_name'][$i];
                                    $path = "images/" . $imgName;
                                    if (move_uploaded_file($tmp, $path)) {
                                        $dbPath = "images/" . $imgName;
                                        $insertImage = "INSERT INTO product_images (product_id, image_path)
                                                    VALUES ('$product_id', '$dbPath')";
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
                                        Product Added Successfully
                                        <?php if ($uploadedImages > 0) echo "(with $uploadedImages images)"; ?>
                                    </h3>
                                    <a href="addproduct.php">Confirm</a>
                                </div>
                            </div>
                <?php
                        } else {
                            $errors['sql'] = "Something went wrong while inserting the product.";
                        }
                    }
                }
                ?>
                <div id="dashboard-container">
                    <img src="../assets/images/background.webp" alt="">
                    <div class="layer">
                        <form method="post" enctype="multipart/form-data">
                            <h2>Add Product</h2>
                            <?php if (isset($errors['fields'])) { ?>
                                <span style="color:red"><?= $errors['fields']; ?></span>
                            <?php } ?>
                            <input type="text" name="productname" placeholder="Product Name">
                            <input type="text" name="productprice" placeholder="Product Price">
                            <input type="text" name="productquantity" placeholder="Product Quantity">
                            <input type="file" name="images[]" multiple>
                            <textarea name="productdescription" placeholder="Product Description"></textarea>
                            <input type="submit" value="Add Product" name="addproduct">
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