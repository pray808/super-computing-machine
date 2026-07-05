<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="2;url=login.php">
    <title>Deconnexion - BTMS</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="login-shell logout-page">
    <main class="logout-screen">
        <section class="logout-card animate__animated animate__fadeIn">
            <div class="brand-mark mx-auto mb-4">
                <i class="bi bi-shield-check"></i>
            </div>

            <span class="eyebrow">Session terminee</span>
            <h1>Deconnexion reussie</h1>
            <p>
                Votre session BTMS a ete fermee correctement. Redirection vers la page de connexion...
            </p>

            <a href="login.php" class="btn btn-command">
                <i class="bi bi-box-arrow-in-right"></i>
                Retour a la connexion
            </a>
        </section>
    </main>
</body>

</html>
