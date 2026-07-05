<?php
include 'includes/db.php';
include 'includes/auth.php';

$total_trafic = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM trafic"))['total'];
$total_infractions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM infractions"))['total'];
$total_urgences = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM urgence"))['total'];
$trafic_eleve = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM trafic WHERE nombre_vehicules >= 30"))['total'];

$dernieres_infractions = mysqli_query(
    $conn,
    "SELECT * FROM infractions ORDER BY id DESC LIMIT 5"
);

$operator_name = isset($_SESSION['nom']) ? $_SESSION['nom'] : 'Operateur';
$operator_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'controle';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BTMS Dashboard</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        #map {
            height: 350px;
            width: 100%;
            border-radius: 8px;
            z-index: 1;
        }
    </style>
</head>

<body class="command-body">
    <div class="app-shell">
        <?php include 'includes/sidebar.php'; ?>

        <main class="command-main">
            <header class="command-topbar" data-aos="fade-down">
                <div>
                    <span class="eyebrow">Centre National de Gestion du Trafic</span>
                    <h1>Bunia Traffic Management System</h1>
                    <p>Supervision intelligente du trafic routier, des infractions et des urgences.</p>
                </div>

                <div class="operator-card">
                    <div class="operator-avatar">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <strong><?php echo htmlspecialchars($operator_name); ?></strong>
                        <span><?php echo htmlspecialchars(strtoupper($operator_role)); ?></span>
                        <small><?php echo date('d/m/Y H:i'); ?></small>
                        <a href="logout.php" class="topbar-logout">
                            <i class="bi bi-box-arrow-right"></i>
                            Se deconnecter
                        </a>
                    </div>
                </div>
            </header>

            <section class="kpi-grid">
                <article class="kpi-card command-card" data-aos="fade-up" data-aos-delay="0">
                    <div class="kpi-icon cyan"><i class="bi bi-car-front-fill"></i></div>
                    <div>
                        <span>Flux routier</span>
                        <strong id="flux-count"><?php echo $total_trafic; ?></strong>
                        <small>+15% aujourd'hui</small>
                    </div>
                </article>

                <article class="kpi-card command-card" data-aos="fade-up" data-aos-delay="80">
                    <div class="kpi-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div>
                        <span>Infractions</span>
                        <strong><?php echo $total_infractions; ?></strong>
                        <small>Controle prioritaire</small>
                    </div>
                </article>

                <article class="kpi-card command-card" data-aos="fade-up" data-aos-delay="160">
                    <div class="kpi-icon green"><i class="bi bi-ambulance"></i></div>
                    <div>
                        <span>Urgences</span>
                        <strong><?php echo $total_urgences; ?></strong>
                        <small>Voies prioritaires</small>
                    </div>
                </article>

                <article class="kpi-card command-card" data-aos="fade-up" data-aos-delay="240">
                    <div class="kpi-icon amber"><i class="bi bi-activity"></i></div>
                    <div>
                        <span>Trafic eleve</span>
                        <strong><?php echo $trafic_eleve; ?></strong>
                        <small>Zones sous tension</small>
                    </div>
                </article>
            </section>

            <section class="dashboard-grid">
                <article class="command-card photo-intel-card" data-aos="zoom-in">
                    <div class="photo-intel-card__content">
                        <span class="eyebrow">Vision terrain</span>
                        <h2>Supervision routiere intelligente</h2>
                        <p>Une interface concue comme un centre de controle moderne : photographie terrain, indicateurs critiques, alertes, carte de Bunia et priorisation des interventions.</p>
                        <div class="intel-chip-row">
                            <span class="intel-chip"><i class="bi bi-camera-video"></i> Surveillance</span>
                            <span class="intel-chip"><i class="bi bi-cpu"></i> IoT ready</span>
                            <span class="intel-chip"><i class="bi bi-shield-check"></i> Police routiere</span>
                            <span class="intel-chip"><i class="bi bi-geo-alt"></i> Bunia</span>
                        </div>
                    </div>
                </article>

                <article class="command-card map-card" data-aos="fade-up">
                    <div class="panel-header">
                        <div>
                            <span>Carte operationnelle</span>
                            <h2>Bunia - zones surveillees</h2>
                        </div>
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <div class="map-container-wrapper">
                        <div id="map"></div>
                    </div>
                </article>

                <article class="command-card" data-aos="fade-up">
                    <div class="panel-header">
                        <div>
                            <span>Evenements recents</span>
                            <h2>Dernieres infractions</h2>
                        </div>
                        <i class="bi bi-camera-video-fill"></i>
                    </div>
                    <div class="table-responsive">
                        <table class="table command-table align-middle">
                            <thead>
                                <tr>
                                    <th>Plaque</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($dernieres_infractions)) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['plaque']); ?></td>
                                        <td><?php echo htmlspecialchars($row['type_infraction']); ?></td>
                                        <td><span class="status-badge danger">A verifier</span></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="command-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="panel-header">
                        <div>
                            <span>Command center</span>
                            <h2>Alertes temps reel</h2>
                        </div>
                        <i class="bi bi-broadcast"></i>
                    </div>
                    <div class="alert-stack" id="alert-container">
                        <div class="text-muted text-center py-3">Initialisation des flux d'alertes...</div>
                    </div>
                </article>

                <article class="command-card system-card" data-aos="fade-up">
                    <div class="panel-header">
                        <div>
                            <span>Infrastructure</span>
                            <h2>Etat du systeme</h2>
                        </div>
                        <i class="bi bi-cpu-fill"></i>
                    </div>
                    <div class="system-list">
                        <div><span class="status-light ok"></span>Base de donnees connectee</div>
                        <div><span class="status-light ok"></span>Application web active</div>
                        <div><span class="status-light ok"></span>Authentification active</div>
                        <div><span class="status-light ok"></span>Gestion du trafic active</div>
                        <div><span class="status-light wait"></span>ESP8266 en attente</div>
                        <div><span class="status-light wait"></span>Raspberry Pi en attente</div>
                        <div><span class="status-light wait"></span>YOLOv8 en attente</div>
                    </div>
                </article>

                <article class="command-card mission-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="panel-header">
                        <div>
                            <span>Objectifs</span>
                            <h2>Mission BTMS</h2>
                        </div>
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="mission-list">
                        <span>Gestion intelligente du trafic routier</span>
                        <span>Detection automatique des infractions</span>
                        <span>Priorite aux vehicules d'urgence</span>
                        <span>Aide a la decision des autorites</span>
                    </div>
                </article>
            </section>
        </main>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        AOS.init({
            duration: 650,
            once: true,
            easing: 'ease-out-cubic'
        });

        // ----------------------------------------------------
        // INITIALISATION DE LEAFLET SUR LA VILLE DE BUNIA
        // ----------------------------------------------------
        const latBunia = 1.564;
        const lngBunia = 30.252;
        const zoomInitial = 14;

        const map = L.map('map').setView([latBunia, lngBunia], zoomInitial);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const marqueursGroup = L.layerGroup().addTo(map);

        function chargerMarqueursCartographiques() {
            fetch('get_points_carte.php')
                .then(response => response.json())
                .then(points => {
                    marqueursGroup.clearLayers();

                    points.forEach(pt => {
                        const marker = L.marker([pt.lat, pt.lng]);

                        let popupContent = `<b>${pt.nom}</b><br>${pt.details}`;
                        if (pt.statut === 'danger') {
                            popupContent += "<br><span style='color:red; font-weight:bold;'>⚠️ Trafic critique detecte</span>";
                        } else if (pt.type === 'infraction') {
                            popupContent += "<br><span style='color:orange; font-weight:bold;'>📸 Infraction enregistree</span>";
                        }

                        marker.bindPopup(popupContent);
                        marqueursGroup.addLayer(marker);
                    });
                })
                .catch(err => console.error('Erreur rafraîchissement cartographique:', err));
        }

        // ----------------------------------------------------
        // CONTRÔLE IOT : PRENDRE LE CONTRÔLE DU FEU À DISTANCE
        // ----------------------------------------------------
        function activerCouloirUrgence(intersection) {
            fetch(`action_urgence.php?zone=${intersection}&statut=force_green`)
                .then(response => response.json())
                .then(data => {
                    alert(`Signal envoyé à l'intersection ${intersection} : Passage au vert prioritaire.`);
                    updateAlertStack();
                })
                .catch(err => console.error("Erreur commande IoT :", err));
        }

        // ----------------------------------------------------
        // REFRESH AUTOMATIQUE DES ALERTES DPUIS LA BDD
        // ----------------------------------------------------
        function updateAlertStack() {
            fetch('get_alertes.php')
                .then(response => response.json())
                .then(alertes => {
                    const container = document.getElementById('alert-container');

                    if (alertes.length === 0) {
                        container.innerHTML = `<div class="text-muted text-center py-3"><i class="bi bi-check-circle"></i> Aucun incident signalé</div>`;
                        return;
                    }

                    let html = '';
                    alertes.forEach(al => {
                        html += `
                            <div class="command-alert ${al.type}">
                                <i class="bi ${al.icone}"></i>
                                <div>
                                    <strong>${al.titre}</strong>
                                    <span>${al.description}</span>
                                    ${al.actionnable ? `
                                        <button onclick="activerCouloirUrgence('${al.zone}')" class="btn btn-sm btn-outline-success mt-2 d-block">
                                            <i class="bi bi-lightning-fill"></i> Activer Couloir Vert (Ouvrir les Feux)
                                        </button>
                                    ` : ''}
                                    ${al.type === 'success' && !al.actionnable ? `
                                        <span class="badge bg-success mt-2 d-inline-block"><i class="bi bi-cpu"></i> Signal Vert Forcé sur le terrain</span>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                })
                .catch(err => console.error('Erreur chargement alertes:', err));
        }

        // Démarrages initiaux
        chargerMarqueursCartographiques();
        updateAlertStack();

        // Cycles d'actualisation asynchrone (Temps réel)
        setInterval(chargerMarqueursCartographiques, 4000);
        setInterval(updateAlertStack, 3000);

        // SCRIPT AJAX EXISTANT (FLUX GLOBAL KPI)
        function updateTraffic() {
            fetch('get_trafic.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('flux-count').innerText = data.count;
                })
                .catch(err => console.error('Erreur AJAX KPI:', err));
        }
        setInterval(updateTraffic, 3000);
    </script>
</body>

</html>