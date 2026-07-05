<?php
include 'includes/db.php';
include 'includes/auth.php';

if (isset($_POST['ajouter'])) {
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $identifiant = mysqli_real_escape_string($conn, $_POST['identifiant']);
    $priorite = mysqli_real_escape_string($conn, $_POST['priorite']);

    mysqli_query(
        $conn,
        "INSERT INTO urgence (type_vehicule, identifiant, niveau_priorite)
         VALUES ('$type', '$identifiant', '$priorite')"
    );

    header("Location: urgence.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une urgence - BTMS</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                <h2>Nouvelle urgence</h2>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Type de vehicule</label>
                        <input type="text" name="type" class="form-control" placeholder="Ambulance" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Identifiant</label>
                        <input type="text" name="identifiant" class="form-control" placeholder="AMB001" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Niveau de priorite</label>
                        <select name="priorite" class="form-control">
                            <option value="1">Priorite faible</option>
                            <option value="2">Priorite moyenne</option>
                            <option value="3">Priorite maximale</option>
                        </select>
                    </div>

                    <button name="ajouter" class="btn btn-primary">
                        <i class="bi bi-check2-circle"></i>
                        Enregistrer
                    </button>
                    <a href="urgence.php" class="btn btn-danger">
                        <i class="bi bi-arrow-left"></i>
                        Retour
                    </a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
