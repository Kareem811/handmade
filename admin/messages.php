<?php
require '../conn.php';
session_start();

if (isset($_SESSION)) {
    if (isset($_SESSION['active'])) {
        if ($_SESSION['active']['role'] !== "admin") {
            header("location: notfound.php");
            exit;
        } else { ?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="css/main.css">
                <link rel="stylesheet" href="css/nav.css">
                <link rel="stylesheet" href="css/dashboard.css">
                <title>Admin - Messages</title>
            </head>

            <body>
                <?php include 'nav.php'; ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $messages = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM `messages`"));
                        foreach ($messages as $message) {
                        ?>
                            <tr>
                                <td><?= $message[0]; ?></td>
                                <td><?= $message[1]; ?></td>
                                <td><?= $message[2]; ?></td>
                                <td><?php if (empty($message[3])) {
                                        echo "This user has no messages";
                                    } else {
                                        echo $message[3];
                                    } ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </body>

            </html>
<?php
        }
    } else {
        header('location: notfound.php');
        exit;
    }
}
?>