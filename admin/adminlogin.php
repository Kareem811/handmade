<?php require '../conn.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Admin Sign in</title>
</head>
<?php
if (isset($_POST['login'])) {
    $pw = trim(htmlspecialchars($_POST['pw']));
    $email = trim(htmlspecialchars($_POST['email']));
    $errors = [];
    if (empty($pw) || empty($email)) {
        $errors['fields'] = "Please fill all fields";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['emailerror'] = "Invalid email format.";
        } else {
            $selectQuery = "SELECT * FROM `users` WHERE `email` = '$email'";
            $selectDone = mysqli_query($conn, $selectQuery);
            $user = mysqli_fetch_assoc($selectDone);
            if (!$user) {
                $errors['notfound'] = "User not found";
            } else {
                $originalPassword = password_verify($pw, $user['password']);
                if (!$originalPassword) {
                    $errors['pwerror'] = "Wrong password";
                } else {
                    session_start();
                    $_SESSION['active'] = $user;
                    header('location: index.php');
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
                    <h1>(Admin) Sign in</h1>
                    <input type="text" placeholder="Email" name="email">
                    <?php
                    if (isset($errors)) {
                        if (isset($errors['fields'])) { ?>
                            <span><?= $errors['fields']; ?></span>
                        <?php }
                        if (isset($errors['emailerror'])) { ?>
                            <span><?= $errors['emailerror']; ?></span>
                        <?php }
                        if (isset($errors['notfound'])) { ?>
                            <span><?= $errors['notfound']; ?></span>
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
                    <input type="submit" value="Sign in" name="login">
                </form>
            </div>
        </div>
    </section>
</body>

</html>