<?php

include 'includes/db.php';
include 'includes/auth.php';

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <meta charset="UTF-8">

    <title>Statistiques BTMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

                <h2>
                    Statistiques et Analyse du Trafic
                </h2>

                <div class="row">

                    <div class="col-md-6">

                        <div class="card">

                            <div class="card-header">

                                Trafic Routier

                            </div>

                            <div class="card-body">

                                <canvas id="traficChart"></canvas>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="card">

                            <div class="card-header">

                                Infractions

                            </div>

                            <div class="card-body">

                                <canvas id="infractionChart"></canvas>

                            </div>

                        </div>

                    </div>

                </div>

                <br>

                <div class="row">

                    <div class="col-md-6">

                        <div class="card">

                            <div class="card-header">

                                Urgences

                            </div>

                            <div class="card-body">

                                <canvas id="urgenceChart"></canvas>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="card">

                            <div class="card-header">

                                Résumé du Système

                            </div>

                            <div class="card-body">

                                <?php

                                $total_trafic = mysqli_num_rows(
                                    mysqli_query($conn, "SELECT * FROM trafic")
                                );

                                $total_infractions = mysqli_num_rows(
                                    mysqli_query($conn, "SELECT * FROM infractions")
                                );

                                $total_urgences = mysqli_num_rows(
                                    mysqli_query($conn, "SELECT * FROM urgence")
                                );

                                ?>

                                <table class="table table-bordered">

                                    <tr>

                                        <th>Indicateur</th>

                                        <th>Valeur</th>

                                    </tr>

                                    <tr>

                                        <td>Total trafic</td>

                                        <td><?= $total_trafic ?></td>

                                    </tr>

                                    <tr>

                                        <td>Total infractions</td>

                                        <td><?= $total_infractions ?></td>

                                    </tr>

                                    <tr>

                                        <td>Total urgences</td>

                                        <td><?= $total_urgences ?></td>

                                    </tr>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        new Chart(
            document.getElementById('traficChart'), {
                type: 'bar',
                data: {
                    labels: [
                        'Lundi',
                        'Mardi',
                        'Mercredi',
                        'Jeudi',
                        'Vendredi',
                        'Samedi',
                        'Dimanche'
                    ],
                    datasets: [{
                        label: 'Trafic',
                        data: [120, 190, 250, 180, 300, 280, 210]
                    }]
                }
            }
        );

        new Chart(
            document.getElementById('infractionChart'), {
                type: 'pie',
                data: {
                    labels: [
                        'Excès vitesse',
                        'Stationnement',
                        'Feu rouge'
                    ],
                    datasets: [{
                        data: [30, 15, 10]
                    }]
                }
            }
        );

        new Chart(
            document.getElementById('urgenceChart'), {
                type: 'doughnut',
                data: {
                    labels: [
                        'Ambulance',
                        'Police',
                        'Protection Civile'
                    ],
                    datasets: [{
                        data: [10, 6, 3]
                    }]
                }
            }
        );
    </script>

</body>

</html>
