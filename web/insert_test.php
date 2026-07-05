<?php

require_once 'config/database.php';

$sql = "INSERT INTO utilisateurs
(
nom,
email,
mot_de_passe,
role
)

VALUES
(
'Admin',
'admin@bunia.cd',
'123456',
'admin'
)";

if (mysqli_query($conn, $sql)) {
    echo "Insertion réussie";
} else {
    echo mysqli_error($conn);
}
