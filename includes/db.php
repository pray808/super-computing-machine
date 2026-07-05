<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "bunia_traffic";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
