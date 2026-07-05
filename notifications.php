<?php

include 'includes/db.php';
include 'includes/auth.php';

$notifications = mysqli_query(
    $conn,
    "SELECT * FROM notifications
ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <title>Notifications BTMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="command-body">

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-2">
                <?php include 'includes/sidebar.php'; ?>
            </div>

            <div class="col-md-10 p-4">

                <h2>Notifications du Système</h2>

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Date</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($notifications)) { ?>

                            <tr>

                                <td><?= $row['id']; ?></td>

                                <td><?= $row['message']; ?></td>

                                <td><?= $row['type_notification']; ?></td>

                                <td><?= $row['statut']; ?></td>

                                <td><?= $row['date_creation']; ?></td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>

</html>
