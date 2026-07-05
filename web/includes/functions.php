<?php

function nettoyer($data)
{
    global $conn;

    return mysqli_real_escape_string(
        $conn,
        htmlspecialchars(trim($data))
    );
}
