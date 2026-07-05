<?php
require_once 'includes/auth.php';
require_once 'config/database.php';

$result = mysqli_query($conn, "SELECT * FROM trafic ORDER BY id DESC LIMIT 80");
$latest = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM trafic ORDER BY id DESC LIMIT 1"));
$total_trafic = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM trafic"))['total'];
$total_infractions = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM infractions"))['total'];
$total_urgences = (int) mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM urgence"))['total'];

$latest_count = $latest ? (int) $latest['nombre_vehicules'] : 0;
$etat_feu = $latest ? $latest['etat_feu'] : 'Automatique';
$vitesse_moyenne = min(85, 28 + (int) round($latest_count * 1.6));
$voie_prioritaire = $latest_count >= 30 ? 'Axe principal' : 'Equilibrage automatique';

$intersections = [
    ['name' => 'Rond-Point Sonas', 'lat' => 1.5666, 'lng' => 30.2527, 'camera' => 'CAM-SONAS-01', 'status' => 'A surveiller', 'vehicles' => max($latest_count, 18), 'speed' => $vitesse_moyenne, 'priority' => $voie_prioritaire],
    ['name' => 'Nyakasanza', 'lat' => 1.5593, 'lng' => 30.2460, 'camera' => 'CAM-NYAKA-02', 'status' => 'Trafic dense', 'vehicles' => max(22, $latest_count - 3), 'speed' => max(20, $vitesse_moyenne - 8), 'priority' => 'Voie Nord'],
    ['name' => 'Mbunya', 'lat' => 1.5719, 'lng' => 30.2415, 'camera' => 'CAM-MBUNYA-03', 'status' => 'Fluide', 'vehicles' => max(8, (int) round($latest_count / 2)), 'speed' => 34, 'priority' => 'Cycle normal'],
    ['name' => 'Kasenyi', 'lat' => 1.5525, 'lng' => 30.2595, 'camera' => 'CAM-KASENYI-04', 'status' => 'Urgence prete', 'vehicles' => max(12, $latest_count - 10), 'speed' => 42, 'priority' => 'Urgence RFID'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte et trafic - BTMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="command-body">
    <div class="app-shell">
        <?php include 'includes/sidebar.php'; ?>
        <main class="command-main">
            <header class="command-topbar">
                <div>
                    <span class="eyebrow">Supervision temps reel</span>
                    <h1>Carte interactive du trafic</h1>
                    <p>Visualisation des intersections, cameras, flux de vehicules, vitesse moyenne et priorite des voies.</p>
                </div>
                <a href="rapports.php" class="btn btn-command"><i class="bi bi-file-earmark-bar-graph"></i> Rapport</a>
            </header>

            <section class="kpi-grid compact-kpis">
                <article class="kpi-card command-card"><div class="kpi-icon cyan"><i class="bi bi-car-front-fill"></i></div><div><span>Lectures trafic</span><strong id="live-total"><?php echo $total_trafic; ?></strong><small>Historique capteurs</small></div></article>
                <article class="kpi-card command-card"><div class="kpi-icon amber"><i class="bi bi-speedometer2"></i></div><div><span>Dernier flux</span><strong id="live-count"><?php echo $latest_count; ?></strong><small>Vehicules detectes</small></div></article>
                <article class="kpi-card command-card"><div class="kpi-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div><div><span>Infractions</span><strong><?php echo $total_infractions; ?></strong><small>Suivi automatique</small></div></article>
                <article class="kpi-card command-card"><div class="kpi-icon green"><i class="bi bi-ambulance"></i></div><div><span>Urgences</span><strong><?php echo $total_urgences; ?></strong><small>Priorite RFID/IA</small></div></article>
            </section>

            <section class="traffic-layout">
                <article class="command-card traffic-map-card">
                    <div class="panel-header">
                        <div><span>OpenStreetMap / Leaflet</span><h2>Points de surveillance</h2></div>
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div id="trafficMap" class="traffic-map"></div>
                </article>

                <aside class="command-card traffic-details" id="intersectionDetails">
                    <div class="panel-header"><div><span>Intersection active</span><h2>Rond-Point Sonas</h2></div><i class="bi bi-camera-video-fill"></i></div>
                    <div class="camera-preview"><i class="bi bi-camera-video"></i><span>Flux multi-cameras</span></div>
                    <dl class="detail-list">
                        <div><dt>Camera</dt><dd id="detail-camera">CAM-SONAS-01</dd></div>
                        <div><dt>Etat trafic</dt><dd id="detail-status">A surveiller</dd></div>
                        <div><dt>Vehicules</dt><dd id="detail-vehicles"><?php echo max($latest_count, 18); ?></dd></div>
                        <div><dt>Vitesse moyenne</dt><dd><span id="detail-speed"><?php echo $vitesse_moyenne; ?></span> km/h</dd></div>
                        <div><dt>Voie prioritaire</dt><dd id="detail-priority"><?php echo htmlspecialchars($voie_prioritaire); ?></dd></div>
                        <div><dt>Etat feu</dt><dd id="detail-light"><?php echo htmlspecialchars($etat_feu); ?></dd></div>
                    </dl>
                </aside>
            </section>

            <article class="command-card mt-4">
                <div class="panel-header"><div><span>Historique IoT</span><h2>Dernieres detections trafic</h2></div><i class="bi bi-clock-history"></i></div>
                <div class="table-responsive">
                    <table class="table command-table align-middle">
                        <thead><tr><th>ID</th><th>Date</th><th>Nombre vehicules</th><th>Etat feu</th><th>Decision</th></tr></thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) { $count = (int) $row['nombre_vehicules']; ?>
                                <tr>
                                    <td><?php echo (int) $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['date_detection']); ?></td>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo htmlspecialchars($row['etat_feu']); ?></td>
                                    <td><span class="status-badge <?php echo $count >= 30 ? 'danger' : 'success'; ?>"><?php echo $count >= 30 ? 'Allonger le vert' : 'Cycle normal'; ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </main>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const intersections = <?php echo json_encode($intersections, JSON_UNESCAPED_SLASHES); ?>;
        const map = L.map('trafficMap').setView([1.565, 30.25], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);

        const details = {
            title: document.querySelector('#intersectionDetails h2'),
            camera: document.getElementById('detail-camera'),
            status: document.getElementById('detail-status'),
            vehicles: document.getElementById('detail-vehicles'),
            speed: document.getElementById('detail-speed'),
            priority: document.getElementById('detail-priority'),
            light: document.getElementById('detail-light')
        };

        function showIntersection(item) {
            details.title.textContent = item.name;
            details.camera.textContent = item.camera;
            details.status.textContent = item.status;
            details.vehicles.textContent = item.vehicles;
            details.speed.textContent = item.speed;
            details.priority.textContent = item.priority;
            details.light.textContent = item.vehicles >= 30 ? 'Vert prolonge' : 'Automatique';
        }

        intersections.forEach((item) => {
            const marker = L.marker([item.lat, item.lng]).addTo(map);
            marker.bindPopup(`<strong>${item.name}</strong><br>${item.camera}<br>${item.vehicles} vehicules`);
            marker.on('click', () => showIntersection(item));
        });

        setInterval(() => {
            fetch('get_trafic.php')
                .then((response) => response.json())
                .then((data) => {
                    document.getElementById('live-total').textContent = data.count;
                    if (data.latest) {
                        document.getElementById('live-count').textContent = data.latest.nombre_vehicules || 0;
                    }
                })
                .catch(() => {});
        }, 4000);
    </script>
</body>
</html>
