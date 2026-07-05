<?php
$current_page = basename($_SERVER['PHP_SELF']);
$role = function_exists('btms_normalized_role') ? btms_normalized_role() : strtolower($_SESSION['role'] ?? '');

$menu_items = [
    ['href' => 'dashboard.php', 'icon' => 'bi-speedometer2', 'label' => 'Dashboard', 'roles' => ['admin', 'pcr', 'direction']],
    ['href' => 'trafic.php', 'icon' => 'bi-map-fill', 'label' => 'Carte & trafic', 'roles' => ['admin', 'pcr', 'direction']],
    ['href' => 'infractions.php', 'icon' => 'bi-exclamation-triangle-fill', 'label' => 'Infractions', 'roles' => ['admin', 'pcr', 'direction']],
    ['href' => 'CAMERA.php', 'icon' => 'bi-exclamation-triangle-fill', 'label' => 'Camera', 'roles' => ['admin', 'pcr', 'direction']],
    ['href' => 'urgence.php', 'icon' => 'bi-ambulance', 'label' => 'Urgences', 'roles' => ['admin', 'pcr', 'direction']],
    ['href' => 'statistiques.php', 'icon' => 'bi-bar-chart-fill', 'label' => 'Statistiques', 'roles' => ['admin', 'direction']],
    ['href' => 'rapports.php', 'icon' => 'bi-file-earmark-bar-graph-fill', 'label' => 'Rapports', 'roles' => ['admin', 'pcr', 'direction']],
    ['href' => 'notifications.php', 'icon' => 'bi-bell-fill', 'label' => 'Notifications', 'roles' => ['admin', 'pcr', 'direction']],
    ['href' => 'utilisateurs.php', 'icon' => 'bi-people-fill', 'label' => 'Utilisateurs', 'roles' => ['admin']],
    ['href' => 'parametres.php', 'icon' => 'bi-gear-fill', 'label' => 'Parametres', 'roles' => ['admin']],
];
?>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-mark">
            <i class="bi bi-sign-intersection-fill"></i>
        </div>
        <div>
            <span>BTMS</span>
            <strong>Traffic Command Center</strong>
        </div>
    </div>

    <div class="sidebar-section">Navigation</div>

    <nav class="sidebar-nav">
        <?php foreach ($menu_items as $item) { ?>
            <?php if (in_array($role, $item['roles'], true)) { ?>
                <a href="<?php echo $item['href']; ?>" class="nav-link <?php echo $current_page === $item['href'] ? 'active' : ''; ?>">
                    <i class="bi <?php echo $item['icon']; ?>"></i>
                    <span><?php echo $item['label']; ?></span>
                </a>
            <?php } ?>
        <?php } ?>
    </nav>

    <div class="sidebar-status">
        <div class="status-dot"></div>
        <div>
            <strong>System online</strong>
            <span>Monitoring Bunia</span>
        </div>
    </div>

    <a href="logout.php" class="logout-link">
        <i class="bi bi-box-arrow-right"></i>
        Deconnexion
    </a>
</aside>