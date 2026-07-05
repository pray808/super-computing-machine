<?php
header('Content-Type: application/json');
include 'includes/db.php';

header("Access-Control-Allow-Origin: *");

$alertes = [];

// 1. Récupération des urgences actives (Basé sur vos vraies colonnes : nom_urgence et plaque_vehicule)
$query_urgences = "SELECT * FROM urgence WHERE statut_feu = 'force_green' OR statut_feu = 'en_attente' ORDER BY id DESC LIMIT 3";
$result_urgences = mysqli_query($conn, $query_urgences);

if ($result_urgences) {
    while ($row = mysqli_fetch_assoc($result_urgences)) {
        $alertes[] = [
            'type' => 'success',
            'icone' => 'bi-ambulance',
            'titre' => htmlspecialchars($row['nom_urgence']) . ' signalée',
            'description' => 'Véhicule ' . htmlspecialchars($row['plaque_vehicule']) . ' demande un couloir vert.',
            'zone' => htmlspecialchars($row['nom_urgence']), // Utilise le nom pour l'actionneur
            'actionnable' => ($row['statut_feu'] !== 'force_green')
        ];
    }
}

// 2. Récupération du trafic critique (Embouteillages)
$query_critique = "SELECT * FROM trafic WHERE nombre_vehicules >= 40 ORDER BY id DESC LIMIT 2";
$result_critique = mysqli_query($conn, $query_critique);

if ($result_critique) {
    while ($row = mysqli_fetch_assoc($result_critique)) {
        $alertes[] = [
            'type' => 'danger',
            'icone' => 'bi-exclamation-octagon',
            'titre' => 'Embouteillage Critique',
            'description' => htmlspecialchars($row['emplacement']) . ' saturé (' . $row['nombre_vehicules'] . ' véhicules)',
            'zone' => htmlspecialchars($row['emplacement']),
            'actionnable' => false
        ];
    }
}

echo json_encode($alertes);
