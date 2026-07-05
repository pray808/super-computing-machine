<?php

include 'includes/db.php';
include 'includes/auth.php';

$total_trafic = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM trafic"
    )
)['total'];

$total_infractions = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM infractions"
    )
)['total'];

$total_urgences = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM urgence"
    )
)['total'];

$today = date('Y-m-d');

$trafic_jour = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
FROM trafic
WHERE DATE(date_detection)=CURDATE()"
    )
)['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8">

    <title>Rapports BTMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="command-body">

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-2 p-0">
                <?php include 'includes/sidebar.php'; ?>
            </div>

            <div class="col-md-10 p-4">

                <h2 class="mb-4">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    Rapports et Statistiques
                </h2>

                <div class="row">

                    <div class="col-md-3">

                        <div class="card bg-primary text-white">

                            <div class="card-body">

                                <h5>Total Trafic</h5>

                                <h1><?= $total_trafic ?></h1>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card bg-danger text-white">

                            <div class="card-body">

                                <h5>Infractions</h5>

                                <h1><?= $total_infractions ?></h1>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card bg-success text-white">

                            <div class="card-body">

                                <h5>Urgences</h5>

                                <h1><?= $total_urgences ?></h1>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card bg-warning">

                            <div class="card-body">

                                <h5>Trafic du Jour</h5>

                                <h1><?= $trafic_jour ?></h1>

                            </div>

                        </div>

                    </div>

                </div>

                <br>

                <div class="card">

                    <div class="card-header">
                        Résumé du Système
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered">

                            <tr>
                                <th>Indicateur</th>
                                <th>Valeur</th>
                            </tr>

                            <tr>
                                <td>Total passages enregistrés</td>
                                <td><?= $total_trafic ?></td>
                            </tr>

                            <tr>
                                <td>Total infractions détectées</td>
                                <td><?= $total_infractions ?></td>
                            </tr>

                            <tr>
                                <td>Total urgences détectées</td>
                                <td><?= $total_urgences ?></td>
                            </tr>

                            <tr>
                                <td>Date du rapport</td>
                                <td><?= date('d/m/Y') ?></td>
                            </tr>

                        </table>

                    </div>

                </div>

                <br>

                <a href="export_pdf.php"
                    class="btn btn-danger">

                    <i class="bi bi-file-earmark-pdf"></i>
                    Exporter PDF

                </a>

            </div>

        </div>

    </div>

</body>

</html>
