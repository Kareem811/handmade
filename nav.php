<?php
// session_start();
$cartCount = 0;
if (isset($_SESSION['active']) && $_SESSION['active']['role'] === 'user') {
    require 'conn.php';
    $uid = $_SESSION['active']['id'];
    $cartCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM cart WHERE user_id=$uid"))['cnt'];
}
?>
<header>
    <nav>
        <a href="/"><img src="/handmade/assets/logo/twitter-logo.png" alt="logo"></a>
        <ul>
            <li><a href="/handmade/index.php">Home</a></li>
            <li><a href="/handmade/about.php">About</a></li>
            <li><a href="/handmade/products.php">Products</a></li>
            <li><a href="/handmade/#contact-container">Contact</a></li>
        </ul>
        <div class="sign">
            <?php if (isset($_SESSION['active'])) { ?>
                <!-- <a href="/handmade/components/profile/profile.php">Welcome <?= $_SESSION['active']['name'] ?></a> -->
                <a href="/handmade/components/profile/profile.php">
                    <img src="/handmade/assets/logo/avatar.webp" alt="">
                </a>
                <?php if ($_SESSION['active']['role'] === "user") { ?>
                    <!-- <a href="/handmade/components/cart/cart.php">Cart (<?= $cartCount ?>)</a> -->
                    <a href="/handmade/components/cart/cart.php" class="cart">
                        <span class="cart-icon">🛒</span>
                        <span class="count"><?= $cartCount ?></span>
                    </a>
                <?php } ?>
                <form method="post" style="display:inline">
                    <button name="logout">Logout</button>
                </form>
            <?php } else { ?>
                <a href="/handmade/login.php">Sign in</a>
                <a href="/handmade/register.php">Sign up</a>
            <?php } ?>
        </div>
    </nav>
</header>
<?php
if (isset($_POST['logout'])) {
    session_destroy();
    header("location: /handmade/index.php");
    exit;
}
?>