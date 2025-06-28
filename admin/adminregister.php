<?php require '../conn.php';
session_start();
ob_start();
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
                <link rel="stylesheet" href="css/dashboard.css">
                <title>Admin Sign up</title>
            </head>
            <?php
            if (isset($_POST['register'])) {
                $userName = trim(htmlspecialchars($_POST['uname']));
                $pw = trim(htmlspecialchars($_POST['pw']));
                $cpw = trim(htmlspecialchars($_POST['cpw']));
                $email = trim(htmlspecialchars($_POST['email']));
                $errors = [];
                if (empty($userName) || empty($pw) || empty($cpw) || empty($email)) {
                    $errors['fields'] = "Please fill all fields";
                } else {
                    if ($pw !== $cpw) {
                        $errors['pwerror'] = "Confirm password should match password";
                    } else {
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $errors['emailerror'] = "Invalid email format.";
                        } else {
                            $selectQuery = "SELECT * FROM `users`";
                            $selectDone = mysqli_query($conn, $selectQuery);
                            $users = mysqli_fetch_all($selectDone);
                            foreach ($users as $user) {
                                if ($user[1] === $userName) {
                                    $errors['unameerror'] = "Username is already used";
                                }
                                if ($user[2] === $email) {
                                    $errors['emailused'] = "Email is already used";
                                }
                            }
                            if (empty($errors)) {
                                $hashedPassword = password_hash($pw, PASSWORD_BCRYPT);
                                $insertQuery = "INSERT INTO `users` (`name` , `password`,`email`,`role`) VALUES ('$userName' , '$hashedPassword' , '$email' , 'admin')";
                                $insertDone = mysqli_query($conn, $insertQuery);
                                if ($insertDone) { ?>
                                    <div class="success">
                                        <div class="content">
                                            <h3>Registed Successfully</h3>
                                            <a href="adminlogin.php">Confirm</a>
                                        </div>
                                    </div>
            <?php }
                            }
                        }
                    }
                }
            }
            ?>

            <body>
                <section id="log-container">
                    <img src="../assets/images/background.webp" alt="">
                    <div class="layer">
                        <div id="admin-log">
                            <form method="post">
                                <h1>(Admin) Sign up</h1>
                                <input type="text" placeholder="Username" name="uname">
                                <?php
                                if (isset($errors)) {
                                    if (isset($errors['fields'])) { ?>
                                        <span><?= $errors['fields']; ?></span>
                                    <?php }
                                    if (isset($errors['unameerror'])) { ?>
                                        <span><?= $errors['unameerror']; ?></span>

                                <?php }
                                }
                                ?>
                                <input type="password" placeholder="Password" name="pw">
                                <?php
                                if (isset($errors)) {
                                    if (isset($errors['fields'])) { ?>
                                        <span><?= $errors['fields']; ?></span>
                                    <?php }
                                    if (isset($errors['pwerror'])) { ?>
                                        <span><?= $errors['pwerror']; ?></span>
                                <?php }
                                }
                                ?>
                                <input type="password" placeholder="Confirm Password" name="cpw">
                                <?php
                                if (isset($errors)) {
                                    if (isset($errors['fields'])) { ?>
                                        <span><?= $errors['fields']; ?></span>
                                    <?php }
                                    if (isset($errors['pwerror'])) { ?>
                                        <span><?= $errors['pwerror']; ?></span>
                                <?php }
                                }
                                ?>
                                <input type="text" placeholder="Email" name="email">
                                <?php
                                if (isset($errors)) {
                                    if (isset($errors['fields'])) { ?>
                                        <span><?= $errors['fields']; ?></span>
                                    <?php }
                                    if (isset($errors['emailerror'])) { ?>
                                        <span><?= $errors['emailerror']; ?></span>
                                    <?php }
                                    if (isset($errors['emailused'])) { ?>
                                        <span><?= $errors['emailused']; ?></span>

                                <?php }
                                }
                                ?>
                                <input type="submit" value="Sign up" name="register">
                            </form>
                        </div>
                    </div>
                </section>
            </body>

            </html>

<?php }
    } else {
        header('location: notfound.php');
    }
}
?>
<?php
ob_end_flush();
