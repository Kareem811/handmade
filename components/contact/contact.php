<section id="contact-container">
    <img src="assets/images/background.webp" alt="">
    <div class="container">
        <?php
        if (isset($_POST['send'])) {
            $userName = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $message = htmlspecialchars($_POST['message']);
            $errors = [];
            if (empty($userName) || empty($email) || empty($message)) {
                $errors['fields'] = "Fields are required";
            } else {
                if (empty($errors)) {
                    $insertDone = mysqli_query($conn, "INSERT INTO `messages` (`username`,`email`,`message`) VALUES ('$userName' , '$email' , '$message')");
                    if ($insertDone) { ?>
                        <div class="success">
                            <div class="content">
                                <h3>Message was sent successfully</h3>
                                <a href="index.php">Confirm</a>
                            </div>
                        </div>
        <?php }
                }
            }
        }
        ?>
        <h2>Contact us</h2>
        <form class="contact-form" method="post">
            <?php
            if (isset($errors)) {
                if (isset($errors['fields'])) {
            ?>
                    <span><?= $errors['fields'] ?></span>
            <?php }
            }
            ?>
            <input type="text" placeholder="Username" name="username">
            <input type="text" placeholder="Email" name="email">
            <textarea placeholder="Message" name="message"></textarea>
            <button type="submit" class="btn" name="send">Send</button>
        </form>
    </div>
</section>