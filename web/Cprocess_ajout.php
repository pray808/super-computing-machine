<?php
include 'includes/db.php';
include 'includes/auth.php';

// Vérification que le formulaire a bien été envoyé
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Vérification de la présence des champs
    if (!empty($_POST['nom']) && !empty($_POST['ip'])) {

        // Nettoyage et sécurisation
        $nom = mysqli_real_escape_string($conn, $_POST['nom']);
        $ip = trim(mysqli_real_escape_string($conn, $_POST['ip'])); // trim() enlève les espaces inutiles

        // Construction de l'URL complète
        // On s'assure de ne pas ajouter de ports en double si l'utilisateur en a déjà saisi un
        $url_complete = "http://" . $ip . ":8080/video";

        // Insertion dans la base de données
        $sql = "INSERT INTO cameras (nom_rond_point, url_flux) VALUES ('$nom', '$url_complete')";

        if (mysqli_query($conn, $sql)) {
            // Redirection vers la page de gestion des caméras
            header("Location: CAMERA.php");
            exit();
        } else {
            echo "Erreur lors de l'enregistrement dans la base de données : " . mysqli_error($conn);
        }
    } else {
        echo "Données manquantes. Veuillez remplir tous les champs du formulaire.";
    }
} else {
    // Si l'utilisateur accède directement au fichier sans passer par le formulaire
    header("Location: CAjouterCam.php");
    exit();
}
