<?php
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$logged = !empty($_SESSION['user_id']);
$basePath = app_base_path();
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
if ($basePath !== '' && ($requestPath === $basePath || str_starts_with($requestPath, $basePath . '/'))) {
    $requestPath = substr($requestPath, strlen($basePath));
}
$current = trim($requestPath, '/');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $e($title) . ' — ' : '' ?>BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $e(app_url('/assets/css/style.css')) ?>" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1; }
        .navbar-brand { font-weight: 700; letter-spacing: .02em; }
        footer { font-size: .875rem; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
<<<<<<< HEAD
        <a class="navbar-brand" href="/"> BiblioNet</a>
=======
        <a class="navbar-brand" href="<?= $e(app_url($logged ? '/dashboard' : '/')) ?>">📚 BiblioNet</a>
>>>>>>> 13da819 (Réglage des erreurs)
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $current === '' || $current === 'catalogue' ? 'active' : '' ?>" href="<?= $e(app_url('/')) ?>">Catalogue</a>
                </li>
                <?php if ($logged): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $current === 'dashboard' ? 'active' : '' ?>" href="<?= $e(app_url('/dashboard')) ?>">Tableau de bord</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($current, 'livres') ? 'active' : '' ?>" href="<?= $e(app_url('/livres')) ?>">Livres</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($current, 'adherents') ? 'active' : '' ?>" href="<?= $e(app_url('/adherents')) ?>">Adhérents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_starts_with($current, 'emprunts') ? 'active' : '' ?>" href="<?= $e(app_url('/emprunts')) ?>">Emprunts</a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($logged): ?>
                <li class="nav-item">
                    <span class="navbar-text text-white-50 me-3"><?= $e($_SESSION['user_nom'] ?? '') ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $e(app_url('/logout')) ?>">Déconnexion</a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $e(app_url('/login')) ?>">Connexion</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container pb-5">
    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= $e($flash['type'] ?? 'info') ?> alert-dismissible fade show" role="alert">
            <?= $e($flash['message'] ?? '') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?= $content ?? '' ?>
</main>

<footer class="bg-light border-top py-3 mt-auto">
    <div class="container text-center text-muted">
        BiblioNet de ELISEE,KATYA,DEBORAH
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
