<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

function btms_user_role(): string
{
    return strtolower($_SESSION['role'] ?? '');
}

function btms_normalized_role(): string
{
    $role = btms_user_role();
    return $role === 'directeur' ? 'direction' : $role;
}

function btms_allowed_pages_by_role(): array
{
    return [
        'admin' => ['*'],
        'pcr' => [
            'dashboard.php',
            'trafic.php',
            'infractions.php',
            'urgence.php',
            'ajouter_urgence.php',
            'notifications.php',
            'rapports.php',
            'export_pdf.php',
            'get_trafic.php',
        ],
        'direction' => [
            'dashboard.php',
            'trafic.php',
            'infractions.php',
            'urgence.php',
            'notifications.php',
            'statistiques.php',
            'rapports.php',
            'export_pdf.php',
            'get_trafic.php',
        ],
    ];
}

function btms_can_access_page(string $page): bool
{
    $role = btms_normalized_role();
    $permissions = btms_allowed_pages_by_role();

    if (!isset($permissions[$role])) {
        return false;
    }

    return in_array('*', $permissions[$role], true) || in_array($page, $permissions[$role], true);
}

function require_role(array $roles): void
{
    $role = btms_normalized_role();
    $roles = array_map(fn($item) => $item === 'directeur' ? 'direction' : strtolower($item), $roles);

    if (!in_array($role, $roles, true)) {
        btms_access_denied();
    }
}

function btms_access_denied(): void
{
    http_response_code(403);
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acces refuse - BTMS</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body class="command-body">
        <main class="container py-5">
            <div class="alert alert-danger border-0 shadow-sm">
                <h1 class="h4">Acces refuse</h1>
                <p class="mb-3">Votre role ne vous autorise pas a ouvrir cette page.</p>
                <a href="dashboard.php" class="btn btn-primary">Retour au tableau de bord</a>
            </div>
        </main>
    </body>
    </html>
    <?php
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
if (!btms_can_access_page($current_page)) {
    btms_access_denied();
}
