<header>
    <nav>
        <a href="/">
            <img src="../assets/logo/twitter-logo.png" alt="">
        </a>
        <ul>
            <li>
                <a href="index.php">Dashboard</a>
            </li>
            <li>
                <a href="products.php">Product</a>
            </li>
            <li>
                <a href="users.php">Users</a>
            </li>
            <li>
                <a href="messages.php">Messages</a>
            </li>
        </ul>
        <div class="sign">
            <div><?= $_SESSION['active']['name']; ?></div>
            <form method="post">
                <input type="submit" value="Logout" name="logout">
            </form>
        </div>
    </nav>
</header>
<?php if (isset($_POST['logout'])) {
    session_destroy();
    header("location: adminlogin.php");
}
