<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

$result = mysqli_query($conn, "SELECT * FROM infractions ORDER BY id DESC");

function montant_amende($type) {
    $type = strtolower((string) $type);
    if (strpos($type, 'vitesse') !== false) return 50000;
    if (strpos($type, 'feu') !== false) return 75000;
    if (strpos($type, 'station') !== false) return 25000;
    return 30000;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infractions - BTMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="command-body">
    <div class="app-shell">
        <?php include 'includes/sidebar.php'; ?>
        <main class="command-main">
            <header class="command-topbar">
                <div><span class="eyebrow">Controle automatise</span><h1>Suivi des infractions routieres</h1><p>Plaques, lieux, types d'infractions, amendes fixes et statut de paiement.</p></div>
                <a href="export_pdf.php" class="btn btn-command"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
            </header>

            <article class="command-card">
                <div class="table-responsive">
                    <table class="table command-table align-middle">
                        <thead><tr><th>ID</th><th>Plaque</th><th>Infraction</th><th>Lieu</th><th>Date</th><th>Amende</th><th>Statut</th></tr></thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) { $amende = montant_amende($row['type_infraction']); $statut = strtolower((string) $row['statut']); ?>
                                <tr>
                                    <td><?php echo (int) $row['id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($row['plaque']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['type_infraction']); ?></td>
                                    <td><?php echo htmlspecialchars($row['lieu']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date_infraction']); ?></td>
                                    <td><?php echo number_format($amende, 0, ',', ' '); ?> FC</td>
                                    <td><span class="status-badge <?php echo (strpos($statut, 'pay') !== false && strpos($statut, 'non') === false) ? 'success' : 'danger'; ?>"><?php echo htmlspecialchars($row['statut']); ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </main>
    </div>
</body>
</html>
