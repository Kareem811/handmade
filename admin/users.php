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
                <title>Admin - Users</title>
            </head>

            <body>
                <?php include 'nav.php'; ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $users = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM `users`"));
                        foreach ($users as $user) {
                        ?>
                            <tr>
                                <td><?= $user[0]; ?></td>
                                <td><?= $user[1]; ?></td>
                                <td><?= $user[2]; ?></td>
                                <td><?php if (empty($user[4])) {
                                        echo "This user has no address";
                                    } else {
                                        echo $user[4];
                                    } ?></td>
                                <td><?php if (empty($user[5])) {
                                        echo "This user has no phone";
                                    } else {
                                        echo $user[5];
                                    } ?></td>
                                <td><?= $user[6]; ?></td>
                                <td>
                                    <form method="post">
                                        <input type="hidden" name="uid" value="<?= $user[0]; ?>">
                                        <input type="submit" value="Update Role" name="updateuser">
                                    </form>
                                </td>
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