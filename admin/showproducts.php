<?php require '../conn.php';
session_start();
if (isset($_SESSION)) {
    if (isset($_SESSION['active'])) {
        if ($_SESSION['active']['role'] !== "admin") {
            header("location: notfound.php");
        } else { ?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="css/main.css">
                <link rel="stylesheet" href="css/nav.css">
                <link rel="stylesheet" href="css/dashboard.css">
                <title>Dashboard - Products</title>
            </head>
            </body>
            <?php include 'nav.php'; ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Product Description</th>
                        <th>Product Quantity</th>
                        <th>Product Images</th>
                        <th>Actions</th>
                    </tr>
                <tbody>
                    <?php
                    $selectQuery = "SELECT products.id, products.name ,products.price, products.description, products.stock_quantity, GROUP_CONCAT(product_images.image_path) as images FROM products LEFT JOIN product_images ON products.id = product_images.product_id GROUP BY products.id";
                    $selectDone = mysqli_query($conn, $selectQuery);
                    $products = mysqli_fetch_all($selectDone);
                    foreach ($products as $product) {
                    ?>
                        <tr>
                            <td><?= $product[0] ?></td>
                            <td><?= $product[1] ?></td>
                            <td><?= $product[2] ?></td>
                            <td><?= $product[3] ?></td>
                            <td><?= $product[4] ?></td>
                            <td>
                                <?php
                                if (empty($product[5]) || $product[5] === NULL) { ?>
                                    <p>This Product has no images..</p>
                                    <?php
                                } else {
                                    $arr = explode(',', $product[5]);
                                    foreach ($arr as $src) { ?>
                                        <img src="<?= $src ?>" alt="<?= $product[1]; ?>" width="60" style="margin:2px" />
                                <?php
                                    }
                                }
                                ?>

                            </td>
                            <td class="actions">
                                <form action="editproduct.php?id=<?php echo $product[0]; ?>" method="post">
                                    <input type="submit" name="upbtn" value="Update">
                                </form>
                                <form method="post">
                                    <input type="hidden" name="delid" value="<?= $product[0]; ?>">
                                    <input type="submit" name="delbtn" value="Delete">
                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                </thead>
            </table>

            </html>
            <?php
            if (isset($_POST['delbtn'])) {
                $delid = $_POST['delid'];
                $getImages = mysqli_query($conn, "SELECT image_path FROM product_images WHERE product_id = $delid");
                if ($getImages) {
                    while ($img = mysqli_fetch_assoc($getImages)) {
                        $imgPath = "../" . $img['image_path'];
                        if (file_exists($imgPath)) {
                            unlink($imgPath);
                        }
                    }
                }
                mysqli_query($conn, "DELETE FROM product_images WHERE product_id = $delid");
                $deleteDone = mysqli_query($conn, "DELETE FROM products WHERE id = $delid");

                if ($deleteDone) {
            ?>
                    <div class='success'>
                        <div class='content'>
                            <h3>Product deleted successfully</h3>
                            <a href='products.php'>Refresh</a>
                        </div>
                    </div>
                    ";
            <?php  }
            }
            ?>

<?php }
    } else {
        header('location: notfound.php');
    }
}
?>