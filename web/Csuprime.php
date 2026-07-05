<?php
include 'includes/db.php';
include 'includes/auth.php';

$id = $_GET['id'];
$conn->query("DELETE FROM cameras WHERE id = $id");
header("Location: CAMERA.php");
