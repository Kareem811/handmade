<?php
require '../../conn.php';
session_start();

if (!isset($_SESSION['active']) || $_SESSION['active']['role'] !== 'user') {
    header("location: login.php");
    exit;
}

$user = $_SESSION['active'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/nav.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 30px auto;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-header {
            background: tomato;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .profile-body {
            padding: 20px;
        }

        .profile-body p {
            font-size: 1.1rem;
            margin: 10px 0;
        }

        .profile-body a {
            display: inline-block;
            margin-top: 20px;
            background: tomato;
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .profile-body a:hover {
            background: #0b5ed7;
        }

        @media (max-width: 600px) {
            .profile-body p {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <?php include '../../nav.php'; ?>

    <div class="profile-container">
        <div class="profile-header">
            <h2>Welcome, <?= $user['name'] ?></h2>
        </div>
        <div class="profile-body">
            <p><strong>Email:</strong> <?= $user['email'] ?></p>
            <p><strong>Role:</strong> <?= $user['role'] ?></p>
            <!-- <a href="/handmade/components/orders/orders.php">View My Orders</a> -->
            <a href="../orders/orders.php">View My Orders</a>
        </div>
    </div>
</body>

</html>