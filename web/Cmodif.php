<?php
include 'includes/db.php';
include 'includes/auth.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Traitement de la mise à jour
if (isset($_POST['update'])) {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $url = mysqli_real_escape_string($conn, $_POST['url']);
    $conn->query("UPDATE cameras SET nom_rond_point='$nom', url_flux='$url' WHERE id=$id");
    header("Location: CAMERA.php");
}

// Récupération des données
$resultat = $conn->query("SELECT * FROM cameras WHERE id=$id");
if ($resultat && $resultat->num_rows > 0) {
    $data = $resultat->fetch_assoc();
?>
    <h2>Modifier la caméra : <?php echo $data['nom_rond_point']; ?></h2>
    <form method="POST">
        <label>Nom du rond-point :</label><br>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($data['nom_rond_point']); ?>" required><br><br>

        <label>URL du flux :</label><br>
        <input type="text" name="url" value="<?php echo htmlspecialchars($data['url_flux']); ?>" required style="width: 300px;"><br><br>

        <button type="submit" name="update">Enregistrer les modifications</button>
        <a href="supprimer_camera.php?id=<?php echo $id; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette caméra ?')">
            <button type="button" style="background-color: red; color: white;">Supprimer cette caméra</button>
        </a>
    </form>
<?php
} else {
    echo "Caméra introuvable ou ID invalide.";
}
?>