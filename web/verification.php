<?php

require_once 'config/database.php';

$sql = "SHOW TABLES";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_array($result)) {
    echo $row[0] . "<br>";
}
