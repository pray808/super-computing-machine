<?php
header('Content-Type: application/json; charset=utf-8');
include(__DIR__ . '/../config/database.php');

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_REQUEST;
}

$nombre = isset($input['nombre']) ? (int) $input['nombre'] : (isset($input['nombre_vehicules']) ? (int) $input['nombre_vehicules'] : null);
if ($nombre === null) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Parametre nombre requis']);
    exit;
}

$type = isset($input['type']) ? mysqli_real_escape_string($conn, $input['type']) : '';
$plaque = isset($input['plaque']) ? mysqli_real_escape_string($conn, $input['plaque']) : 'INCONNUE';
$lieu = isset($input['lieu']) ? mysqli_real_escape_string($conn, $input['lieu']) : 'Point de surveillance BTMS';
$vitesse = isset($input['vitesse']) ? (int) $input['vitesse'] : 0;
$urgence = isset($input['urgence']) && in_array(strtolower((string) $input['urgence']), ['1', 'true', 'oui', 'yes'], true);
$etat_feu = $urgence ? 'Priorite urgence' : ($nombre >= 30 ? 'Vert prolonge' : 'Automatique');

mysqli_query($conn, "INSERT INTO trafic (date_detection, nombre_vehicules, etat_feu) VALUES (NOW(), $nombre, '$etat_feu')");
$trafic_id = mysqli_insert_id($conn);
$created = ['trafic_id' => $trafic_id];

if ($nombre >= 30) {
    $message = mysqli_real_escape_string($conn, "Trafic eleve detecte a $lieu ($nombre vehicules)");
    mysqli_query($conn, "INSERT INTO notifications (message, type_notification, statut) VALUES ('$message', 'trafic', 'Non lue')");
    $created['notification_trafic'] = mysqli_insert_id($conn);
}

if ($vitesse > 50 || $type !== '') {
    $infraction = $type !== '' ? $type : 'Exces de vitesse';
    mysqli_query($conn, "INSERT INTO infractions (plaque, type_infraction, lieu, date_infraction, statut) VALUES ('$plaque', '$infraction', '$lieu', NOW(), 'Non paye')");
    $created['infraction_id'] = mysqli_insert_id($conn);

    $message = mysqli_real_escape_string($conn, "Infraction detectee: $infraction - plaque $plaque - $lieu");
    mysqli_query($conn, "INSERT INTO notifications (message, type_notification, statut) VALUES ('$message', 'infraction', 'Non lue')");
    $created['notification_infraction'] = mysqli_insert_id($conn);
}

if ($urgence) {
    $identifiant = $plaque !== 'INCONNUE' ? $plaque : 'RFID-URGENCE';
    mysqli_query($conn, "INSERT INTO urgence (type_vehicule, identifiant, niveau_priorite, statut) VALUES ('Vehicule prioritaire', '$identifiant', 5, 'Prioritaire')");
    $created['urgence_id'] = mysqli_insert_id($conn);

    $message = mysqli_real_escape_string($conn, "Vehicule d'urgence prioritaire detecte a $lieu");
    mysqli_query($conn, "INSERT INTO notifications (message, type_notification, statut) VALUES ('$message', 'urgence', 'Non lue')");
    $created['notification_urgence'] = mysqli_insert_id($conn);
}

echo json_encode(['success' => true, 'etat_feu' => $etat_feu, 'created' => $created]);
