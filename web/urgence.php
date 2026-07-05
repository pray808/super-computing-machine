<?php

include 'includes/db.php';
include 'includes/auth.php';

$result = mysqli_query(
    $conn,
    "SELECT * FROM urgence
ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <title>Gestion des Urgences</title>

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

            <div class="col-md-10 p-4">

                <h2>
                    Gestion des Véhicules d'Urgence
                </h2>

                <?php if (in_array(btms_normalized_role(), ['admin', 'pcr'], true)) { ?>
                    <a href="ajouter_urgence.php" class="btn btn-success mb-3">
                        Ajouter une urgence
                    </a>
                <?php } ?>

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Type</th>

                            <th>Identifiant</th>

                            <th>Priorité</th>

                            <th>Date</th>

                            <th>Statut</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                            <tr>

                                <td><?= $row['id']; ?></td>

                                <td><?= $row['type_vehicule']; ?></td>

                                <td><?= $row['identifiant']; ?></td>

                                <td><?= $row['niveau_priorite']; ?></td>

                                <td><?= $row['date_detection']; ?></td>

                                <td><?= $row['statut']; ?></td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>

</html>
