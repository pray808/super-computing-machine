<?php
require_once 'includes/auth.php';
require_role(['admin']);
require_once 'config/database.php';

$erreur = "";

if (isset($_POST['ajouter'])) {
    $nom = mysqli_real_escape_string($conn, trim($_POST['nom']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    $verif_email = mysqli_query($conn, "SELECT id FROM utilisateurs WHERE email = '$email'");

    if (mysqli_num_rows($verif_email) > 0) {
        $erreur = "Cette adresse email est deja utilisee par un autre compte.";
    } else {
        $query = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role)
                  VALUES ('$nom', '$email', '$mot_de_passe', '$role')";

        if (mysqli_query($conn, $query)) {
            header("Location: utilisateurs.php");
            exit();
        }

        $erreur = "Une erreur est survenue lors de l'enregistrement : " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur - BTMS</title>

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
                <h2>Nouvel utilisateur</h2>

                <?php if (!empty($erreur)) { ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-shield-exclamation me-2"></i>
                        <?= htmlspecialchars($erreur); ?>
                    </div>
                <?php } ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="nom" class="form-control" placeholder="Entrez le nom" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse email</label>
                        <input type="email" name="email" class="form-control" placeholder="exemple@bunia.cd" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="mot_de_passe" class="form-control" placeholder="Mot de passe securise" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Role / fonction</label>
                        <select name="role" class="form-control">
                            <option value="admin">Administrateur</option>
                            <option value="pcr">PCR - Police de Circulation Routiere</option>
                            <option value="direction">Directeur / Direction</option>
                        </select>
                    </div>

                    <button type="submit" name="ajouter" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i>
                        Ajouter l'utilisateur
                    </button>
                    <a href="utilisateurs.php" class="btn btn-danger">
                        <i class="bi bi-arrow-left"></i>
                        Retour
                    </a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
