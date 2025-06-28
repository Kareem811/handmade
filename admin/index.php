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
                <title>Dashboard</title>
            </head>

            <body>
                <?php include 'nav.php'; ?>
                <div id="dashboard-container">
                    <h1>Welcome <?= $_SESSION['active']['name'] ?></h1>
                    <span>Dashboard Content</span>
                    <div class="dashboard-content">
                        <a href="products.php">Products</a>
                        <a href="users.php">Users</a>
                        <a href="messages.php">Messages</a>
                    </div>
                </div>
            </body>

            </html>
<?php }
    } else {
        header('location: notfound.php');
    }
}
?>