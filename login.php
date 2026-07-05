<?php
session_start();

require_once 'config/database.php';
include 'includes/db.php';

$message = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    $query_str = "SELECT * FROM utilisateurs WHERE email='$email' LIMIT 1";
    $query = mysqli_query($conn, $query_str);

    if ($query === false) {
        die("Erreur SQL : " . mysqli_error($conn));
    }

    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        $stored_password = $user['mot_de_passe'];
        $password_ok = password_verify($password, $stored_password) || hash_equals($stored_password, md5($password));

        if ($password_ok) {
            if (hash_equals($stored_password, md5($password))) {
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                $user_id = (int) $user['id'];
                mysqli_query($conn, "UPDATE utilisateurs SET mot_de_passe='" . mysqli_real_escape_string($conn, $new_hash) . "' WHERE id=$user_id");
            }

            $_SESSION['id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit();
        }
    }

    $message = "Email ou mot de passe incorrect.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - BTMS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="login-shell">
    <main class="login-layout">
        <section class="login-hero" aria-label="Centre de supervision routiere">
            <div class="login-hero__overlay"></div>
            <div class="login-hero__content animate__animated animate__fadeInUp">
                <div class="system-pill">
                    <i class="bi bi-broadcast-pin"></i>
                    Centre operationnel actif
                </div>
                <h1>Ville de Bunia<br>Police Routiere</h1>
                <p>Gestion intelligente du trafic, des urgences et des infractions routieres.</p>

                <div class="hero-metrics">
                    <div>
                        <strong>24/7</strong>
                        <span>Supervision</span>
                    </div>
                    <div>
                        <strong>IoT</strong>
                        <span>Capteurs</span>
                    </div>
                    <div>
                        <strong>AI</strong>
                        <span>Analyse</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="login-panel">
            <div class="login-card-pro animate__animated animate__fadeIn">
                <div class="brand-lockup">
                    <div class="brand-mark">
                        <i class="bi bi-sign-intersection-fill"></i>
                    </div>
                    <div>
                        <span>BTMS</span>
                        <strong>Traffic Command Center</strong>
                    </div>
                </div>

                <div class="login-title">
                    <p>Acces securise</p>
                    <h2>Connexion operateur</h2>
                </div>

                <?php if (!empty($message)) { ?>
                    <div class="alert alert-danger border-0">
                        <i class="bi bi-shield-exclamation me-2"></i>
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php } ?>

                <form method="POST">
                    <div class="form-group-pro">
                        <label for="email">Email</label>
                        <div class="input-shell">
                            <i class="bi bi-envelope"></i>
                            <input id="email" type="email" name="email" class="form-control" placeholder="operateur@btms.cd" required>
                        </div>
                    </div>

                    <div class="form-group-pro">
                        <label for="password">Mot de passe</label>
                        <div class="input-shell">
                            <i class="bi bi-lock"></i>
                            <input id="password" type="password" name="password" class="form-control" placeholder="Votre mot de passe" required>
                        </div>
                    </div>

                    <button type="submit" name="login" class="btn btn-command w-100">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Se connecter
                    </button>
                </form>

                <div class="secure-note">
                    <i class="bi bi-shield-check"></i>
                    Plateforme de supervision routiere - Bunia
                </div>
            </div>
        </section>
    </main>
</body>

</html>