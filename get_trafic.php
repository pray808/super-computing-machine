<?php
header('Content-Type: application/json; charset=utf-8');
require_once 'config/database.php';

$res = mysqli_query($conn, "SELECT COUNT(*) AS total FROM trafic");
$data = mysqli_fetch_assoc($res);
$latest_result = mysqli_query($conn, "SELECT id, date_detection, nombre_vehicules, etat_feu FROM trafic ORDER BY id DESC LIMIT 1");
$latest = $latest_result ? mysqli_fetch_assoc($latest_result) : null;

echo json_encode([
    'count' => (int) $data['total'],
    'latest' => $latest,
]);
