<?php
require '../conn.php';
session_start();
if (isset($_SESSION)) {
    if (!isset($_SESSION['active'])) {
        header('location: notfound.php');
    } else {
        if ($_SESSION['active']['role'] !== "admin") {
            header('location: notfound.php');
        } else {
?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="css/main.css">
                <link rel="stylesheet" href="css/nav.css">
                <link rel="stylesheet" href="css/dashboard.css">
                <title>Admin - Products</title>
            </head>

            <body>
                <?php include 'nav.php'; ?>
                <section id="selection-container">
                    <img src="../assets/images/background.webp" alt="">
                    <div class="layer">
                        <a href="addproduct.php">Add Product</a>
                        <a href="showproducts.php">Show Products</a>
                    </div>
                </section>
            </body>

            </html>
<?php
        }
    }
}
