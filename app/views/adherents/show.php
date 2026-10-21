<?php $e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $e($adherent['prenom'] . ' ' . $adherent['nom']) ?> - BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
    <h1 class="h3 mb-3">Fiche adhérent</h1>
    <dl class="row">
        <dt class="col-sm-3">Nom</dt>
        <dd class="col-sm-9"><?= $e($adherent['nom']) ?></dd>
        <dt class="col-sm-3">Prénom</dt>
        <dd class="col-sm-9"><?= $e($adherent['prenom']) ?></dd>
        <dt class="col-sm-3">Classe</dt>
        <dd class="col-sm-9"><?= $e($adherent['classe']) ?></dd>
        <dt class="col-sm-3">Téléphone</dt>
        <dd class="col-sm-9"><?= $e($adherent['telephone'] ?? '') ?></dd>
    </dl>

    <h2 class="h5 mt-4">Historique des emprunts</h2>
    <?php if (empty($emprunts)): ?>
        <p class="text-muted">Aucun emprunt enregistré pour cet adhérent.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr><th>Livre</th><th>Emprunté le</th><th>Retourné le</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($emprunts as $emprunt): ?>
                        <tr>
                            <td><?= $e($emprunt['livre_titre']) ?></td>
                            <td><?= $e($emprunt['date_emprunt']) ?></td>
                            <td><?= $e($emprunt['date_retour'] ?? 'En cours') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="<?= $e(app_url('/adherents')) ?>" class="btn btn-outline-secondary">Retour à la liste</a>
    <a href="<?= $e(app_url('/adherents/edit/' . (int) $adherent['id'])) ?>" class="btn btn-primary">Modifier</a>
</body>
</html>
