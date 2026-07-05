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

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .camera-card {
            background: #2c3e50;
            padding: 15px;
            border-radius: 10px;
        }

        img {
            width: 100%;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .add-btn {
            background: #27ae60;
            color: white;
            padding: 15px;
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 10px;
        }

        .btn-edit {
            background: #f39c12;
            padding: 5px 15px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-delete {
            background: #e74c3c;
            padding: 5px 15px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <h1>Centre de Commandement - Bunia</h1>
    <a href="CAjouterCam.php" class="add-btn">+ Ajouter Caméra</a>

    <div class="grid-container">
        <?php
        $res = mysqli_query($conn, "SELECT * FROM cameras");
        while ($row = mysqli_fetch_assoc($res)) {
            // URL du serveur IA + URL de la caméra encodée
            $url_ia = "http://localhost:5000/video_feed/" . urlencode($row['url_flux']);

            echo '<div class="camera-card">
                    <img src="' . $url_ia . '" alt="Flux IA">
                    <div class="camera-info">
                        <h3>' . htmlspecialchars($row['nom_rond_point']) . '</h3>
                        <div class="actions">
                            <a href="Cmodif.php?id=' . $row['id'] . '" class="btn-edit">Modifier</a>
                            <a href="Csuprime.php?id=' . $row['id'] . '" class="btn-delete" onclick="return confirm(\'Confirmer la suppression ?\')">Supprimer</a>
                        </div>
                    </div>
                  </div>';
        }
        ?>
    </div>
</body>

</html>