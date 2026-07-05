<?php

require_once 'includes/auth.php';

if ($_SESSION['role'] != 'admin') {
    die("Accès refusé");
}

require_once 'includes/auth.php';
require_once 'config/database.php';

$result = mysqli_query(
    $conn,
    "SELECT * FROM utilisateurs ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Utilisateurs</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body class="command-body">

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-2">
                <?php include 'includes/sidebar.php'; ?>
            </div>

            <div class="col-md-10">

                <h2 class="mt-3">
                    Gestion des Utilisateurs
                </h2>

                <a href="ajouter_utilisateur.php"
                    class="btn btn-success mb-3">
                    Ajouter
                </a>

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                            <tr>

                                <td><?= $row['id']; ?></td>
                                <td><?= $row['nom']; ?></td>
                                <td><?= $row['email']; ?></td>
                                <td><?= $row['role']; ?></td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>

</html>
