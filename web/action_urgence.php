<?php
header('Content-Type: application/json');
include 'includes/db.php';

// Permet de gérer les requêtes provenant de l'ESP8266 ou Raspberry Pi
header("Access-Control-Allow-Origin: *");

$response = ["status" => "error", "message" => "Action non spécifiée"];

// CAS 1 : Le Dashboard clique sur le bouton "Couloir Vert"
if (isset($_GET['zone']) && isset($_GET['statut'])) {
    $zone = mysqli_real_escape_string($conn, $_GET['zone']); //  Correct (string au lieu de space)
    $statut = mysqli_real_escape_string($conn, $_GET['statut']); // ex: 'force_green'

    // On met à jour la table urgence pour cette intersection
    $query = "UPDATE urgence SET statut_feu = '$statut', mis_a_jour_le = NOW() WHERE intersection_nom = '$zone'";

    if (mysqli_query($conn, $query)) {
        // Si aucune ligne n'a été modifiée (parce que l'intersection n'existait pas encore), on l'insère
        if (mysqli_affected_rows($conn) == 0) {
            mysqli_query($conn, "INSERT INTO urgence (intersection_nom, statut_feu, mis_a_jour_le) VALUES ('$zone', '$statut', NOW())");
        }
        $response = ["status" => "success", "message" => "Ordre envoyé à l'intersection $zone : $statut"];
    } else {
        $response = ["status" => "error", "message" => "Erreur SQL : " . mysqli_error($conn)];
    }
}

// CAS 2 : L'ESP8266 (le feu connecté sur le terrain) demande s'il doit passer au vert
if (isset($_GET['get_feu_statut_for'])) {
    $zone = mysqli_real_escape_string($conn, $_GET['get_feu_statut_for']);

    $query = "SELECT statut_feu FROM urgence WHERE intersection_nom = '$zone' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        echo json_encode(["statut" => $row['statut_feu']]);
        exit;
    } else {
        echo json_encode(["statut" => "normal"]);
        exit;
    }
}

echo json_encode($response);
