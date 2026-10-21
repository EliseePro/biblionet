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
    <title>Emprunts - BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
<<<<<<< HEAD
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Gestion des emprunts</h1>
        <a href="<?= $e(app_url('/emprunts/create')) ?>" class="btn btn-primary">+ Ajouter</a>
=======

    <div class="d-flex justify-content-between mb-3">
        <h1 class="h3">Emprunts</h1>
        <div>
            <a href="/" class="btn btn-outline-secondary btn-sm me-2">Catalogue</a>
            <a href="/emprunts/create" class="btn btn-primary">+ Nouvel emprunt</a>
        </div>
>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
    </div>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= $e($flash['type']) ?>"><?= $e($flash['message']) ?></div>
    <?php endif; ?>

<<<<<<< HEAD
    <?php if (empty($emprunts)): ?>
        <p class="text-muted">Aucun emprunt enregistré.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Adhérent</th>
                        <th>Classe</th>
                        <th>Livre</th>
                        <th>Emprunté le</th>
                        <th>Retourné le</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emprunts as $emprunt): ?>
                        <tr>
                            <td><?= $e($emprunt['adherent_nom'] . ' ' . $emprunt['adherent_prenom']) ?></td>
                            <td><?= $e($emprunt['adherent_classe']) ?></td>
                            <td><?= $e($emprunt['livre_titre']) ?></td>
                            <td><?= $e($emprunt['date_emprunt']) ?></td>
                            <td>
                                <?php if ($emprunt['date_retour'] === null): ?>
                                    <span class="badge text-bg-warning">En cours</span>
                                <?php else: ?>
                                    <?= $e($emprunt['date_retour']) ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-nowrap">
                                <a href="<?= $e(app_url('/emprunts/edit/' . (int) $emprunt['id'])) ?>"
                                   class="btn btn-sm btn-outline-primary">Modifier</a>
                                <?php if ($emprunt['date_retour'] === null): ?>
                                    <form action="<?= $e(app_url('/emprunts/return/' . (int) $emprunt['id'])) ?>" method="post"
                                          class="d-inline"
                                          onsubmit="return confirm('Confirmer le retour de ce livre ?')">
                                        <button type="submit" class="btn btn-sm btn-outline-success">Retourner</button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?= $e(app_url('/emprunts/delete/' . (int) $emprunt['id'])) ?>" method="post"
                                      class="d-inline" onsubmit="return confirm('Supprimer cet emprunt ?')">
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
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Livre</th>
                <th>Adhérent</th>
                <th>Date emprunt</th>
                <th>Date retour</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($emprunts)): ?>
            <tr><td colspan="6" class="text-center text-muted">Aucun emprunt.</td></tr>
        <?php else: ?>
            <?php foreach ($emprunts as $em): ?>
                <?php $enCours = empty($em['date_retour']); ?>
                <tr>
                    <td><?= $e($em['livre_titre'] ?? '') ?></td>
                    <td><?= $e($em['adherent_nom'] ?? '') ?></td>
                    <td><?= $e($em['date_emprunt'] ?? '') ?></td>
                    <td><?= $e($em['date_retour'] ?? '—') ?></td>
                    <td>
                        <?php if ($enCours): ?>
                            <span class="badge text-bg-warning">En cours</span>
                        <?php else: ?>
                            <span class="badge text-bg-success">Rendu</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($enCours): ?>
                            <form action="/emprunts/retour/<?= (int) $em['id'] ?>" method="post" class="d-inline">
                                <button class="btn btn-sm btn-success">Retour</button>
                            </form>
                        <?php endif; ?>
                        <form action="/emprunts/delete/<?= (int) $em['id'] ?>" method="post" class="d-inline"
                              onsubmit="return confirm('Supprimer cet emprunt ?')">
                            <button class="btn btn-sm btn-outline-danger">Suppr.</button>
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
