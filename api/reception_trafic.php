<?php

require_once '../config/database.php';

$vehicules = $_POST['vehicules'];
$etat = $_POST['etat'];

$sql = "
INSERT INTO trafic
(
date_detection,
nombre_vehicules,
etat_feu
)

VALUES

(
NOW(),
'$vehicules',
'$etat'
)
";

mysqli_query($conn, $sql);

echo "OK";
