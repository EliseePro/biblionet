<<<<<<< HEAD
<?php $e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>
=======
<?php
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <meta name="viewport" content="width=device-width, initial-scale=1">
=======
>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
    <title>Adhérents - BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
<<<<<<< HEAD
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Gestion des adhérents</h1>
        <a href="<?= $e(app_url('/adherents/create')) ?>" class="btn btn-primary">+ Ajouter</a>
=======

    <div class="d-flex justify-content-between mb-3">
        <h1 class="h3">Gestion des adhérents</h1>
        <div>
            <a href="/" class="btn btn-outline-secondary btn-sm me-2">Catalogue</a>
            <a href="/adherents/create" class="btn btn-primary">+ Ajouter</a>
        </div>
>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
    </div>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= $e($flash['type']) ?>"><?= $e($flash['message']) ?></div>
    <?php endif; ?>

<<<<<<< HEAD
    <?php if (empty($adherents)): ?>
        <p class="text-muted">Aucun adhérent enregistré.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Classe</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($adherents as $a): ?>
                        <tr>
                            <td><?= $e($a['nom']) ?></td>
                            <td><?= $e($a['prenom']) ?></td>
                            <td><?= $e($a['classe']) ?></td>
                            <td><?= $e($a['telephone'] ?? '') ?></td>
                            <td class="text-nowrap">
                                <a href="<?= $e(app_url('/adherents/show/' . (int) $a['id'])) ?>"
                                   class="btn btn-sm btn-outline-secondary">Détails</a>
                                <a href="<?= $e(app_url('/adherents/edit/' . (int) $a['id'])) ?>"
                                   class="btn btn-sm btn-outline-primary">Modifier</a>
                                <form action="<?= $e(app_url('/adherents/delete/' . (int) $a['id'])) ?>" method="post"
                                      class="d-inline" onsubmit="return confirm('Supprimer cet adhérent ?')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
=======
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nom</th><th>Prénom</th><th>Classe</th><th>Téléphone</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($adherents)): ?>
            <tr><td colspan="5" class="text-center text-muted">Aucun adhérent.</td></tr>
        <?php else: ?>
            <?php foreach ($adherents as $a): ?>
                <tr>
                    <td><?= $e($a['nom']) ?></td>
                    <td><?= $e($a['prenom']) ?></td>
                    <td><?= $e($a['classe']) ?></td>
                    <td><?= $e($a['telephone'] ?? '') ?></td>
                    <td>
                        <a href="/adherents/edit/<?= (int) $a['id'] ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                        <form action="/adherents/delete/<?= (int) $a['id'] ?>" method="post" class="d-inline"
                              onsubmit="return confirm('Supprimer cet adhérent ?')">
                            <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
</body>
</html>
