<?php
include 'includes/db.php';
include 'includes/auth.php';

?>
<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            background: #1a1a1a;
            color: white;
            font-family: sans-serif;
            padding: 20px;
        }

        .form-container {
            background: #2c3e50;
            padding: 20px;
            border-radius: 10px;
            max-width: 400px;
            margin: auto;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }

        button {
            background: #27ae60;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Ajouter une caméra</h2>
        <form action="Cprocess_ajout.php" method="POST">
            <input type="text" name="nom" placeholder="Nom du rond-point" required>
            <input type="text" name="ip" placeholder="Adresse IP" required>
            <button type="submit">Enregistrer</button>
        </form>
    </div>
</body>

</html>