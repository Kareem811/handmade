<?php require 'conn.php';
session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/home.css">
    <title>Handmade - Home</title>
</head>

<body>
    <?php
    include 'nav.php';
    include 'components/background/background.php';
    // include 'components/categories/categories.php';
    include 'components/best-selling/best-selling.php';
    include 'components/contact/contact.php';
    include 'components/footer/footer.php';
    ?>

    <script src="js/nav.js"></script>
</body>

</html>