<?php $e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Livres - BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <div class="d-flex justify-content-between mb-3">
        <h1 class="h3">Gestion des livres</h1>
        <a href="/livres/create" class="btn btn-primary">+ Ajouter</a>
    </div>

    <?php if ($flash): ?>
        <div class="alert alert-<?= $e($flash['type']) ?>"><?= $e($flash['message']) ?></div>
    <?php endif; ?>

    <table class="table table-striped">
        <tr>
            <th>Titre</th><th>Auteur</th><th>Catégorie</th><th>Année</th>
            <th>Exemplaires</th><th>Disponibles</th><th>Actions</th>
        </tr>
        <?php foreach ($livres as $l): ?>
            <tr>
                <td><?= $e($l['titre']) ?></td>
                <td><?= $e($l['auteur']) ?></td>
                <td><?= $e($l['categorie']) ?></td>
                <td><?= $e($l['annee']) ?></td>
                <td><?= $e($l['nb_exemplaires']) ?></td>
                <td><?= $e($l['disponibles']) ?></td>
                <td>
                    <a href="/livres/edit/<?= $e($l['id']) ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                    <form action="/livres/delete/<?= $e($l['id']) ?>" method="post" class="d-inline"
                          onsubmit="return confirm('Supprimer ce livre ?')">
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>