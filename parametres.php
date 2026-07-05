<?php

include 'includes/db.php';
include 'includes/auth.php';

if (isset($_POST['modifier'])) {

    $nom_systeme = $_POST['nom_systeme'];
    $ville = $_POST['ville'];
    $seuil_trafic = $_POST['seuil_trafic'];
    $seuil_urgence = $_POST['seuil_urgence'];

    mysqli_query(
        $conn,

        "UPDATE parametres SET

nom_systeme='$nom_systeme',
ville='$ville',
seuil_trafic='$seuil_trafic',
seuil_urgence='$seuil_urgence'

WHERE id=1"
    );
}

$config = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM parametres WHERE id=1"
    )
);

?>

<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <title>Paramètres BTMS</title>

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

                <h2>Paramètres du Système</h2>

                <form method="POST">

                    <label>Nom du système</label>

                    <input
                        type="text"
                        name="nom_systeme"
                        class="form-control"
                        value="<?= $config['nom_systeme']; ?>">

                    <br>

                    <label>Ville</label>

                    <input
                        type="text"
                        name="ville"
                        class="form-control"
                        value="<?= $config['ville']; ?>">

                    <br>

                    <label>Seuil Trafic Élevé</label>

                    <input
                        type="number"
                        name="seuil_trafic"
                        class="form-control"
                        value="<?= $config['seuil_trafic']; ?>">

                    <br>

                    <label>Seuil Priorité Urgence</label>

                    <input
                        type="number"
                        name="seuil_urgence"
                        class="form-control"
                        value="<?= $config['seuil_urgence']; ?>">

                    <br>

                    <button
                        name="modifier"
                        class="btn btn-primary">

                        Enregistrer

                    </button>

                </form>

            </div>

        </div>

    </div>

</body>

</html>
