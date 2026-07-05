<?php
header('Content-Type: application/json');
include 'includes/db.php';

$points = [];

// 1. Récupération des données de trafic (ex: capteurs, caméras, feux)
// On suppose que votre table 'trafic' contient l'emplacement, la latitude, la longitude et le nombre de véhicules
$query_trafic = "SELECT emplacement, latitude, longitude, nombre_vehicules FROM trafic WHERE latitude IS NOT NULL AND longitude IS NOT NULL";
$result_trafic = mysqli_query($conn, $query_trafic);

if ($result_trafic) {
    while ($row = mysqli_fetch_assoc($result_trafic)) {
        // On détermine un type ou un statut en fonction du flux
        $statut = ($row['nombre_vehicules'] >= 30) ? 'danger' : 'normal';

        $points[] = [
            'nom' => $row['emplacement'],
            'lat' => (float)$row['latitude'],
            'lng' => (float)$row['longitude'],
            'type' => 'trafic',
            'statut' => $statut,
            'details' => "Flux : " . $row['nombre_vehicules'] . " véhicules"
        ];
    }
}

// 2. Récupération des infractions récentes (Optionnel mais recommandé pour votre objectif)
$query_infractions = "SELECT type_infraction, latitude, longitude, plaque FROM infractions WHERE latitude IS NOT NULL AND longitude IS NOT NULL ORDER BY id DESC LIMIT 10";
$result_infractions = mysqli_query($conn, $query_infractions);

if ($result_infractions) {
    while ($row = mysqli_fetch_assoc($result_infractions)) {
        $points[] = [
            'nom' => "Infraction : " . $row['type_infraction'],
            'lat' => (float)$row['latitude'],
            'lng' => (float)$row['longitude'],
            'type' => 'infraction',
            'statut' => 'warning',
            'details' => "Véhicule immatriculé : " . $row['plaque']
        ];
    }
}

// On renvoie le tout encodé en JSON pour le JavaScript
echo json_encode($points);
