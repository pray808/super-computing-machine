<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "bunia_traffic";

$conn = mysqli_connect(
    $host,
    $user,
    $password,
    $database
);

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

?>