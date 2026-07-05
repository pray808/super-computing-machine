<?php
include 'includes/db.php';
include 'includes/auth.php';
// Exemple de récupération en PHP pour votre dashboard
$sql = "SELECT * FROM cameras";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    // Cette partie générera dynamiquement vos cartes de caméras
    echo '<div class="camera-card">';
    echo '<img src="' . $row['url_flux'] . '" alt="' . $row['nom_rond_point'] . '">';
    echo '<div class="camera-info"><h3>' . $row['nom_rond_point'] . '</h3></div>';
    echo '</div>';
}
