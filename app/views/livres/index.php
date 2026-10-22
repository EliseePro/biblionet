<?php
/** @var array $livres */
/** @var array|null $flash */
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$indisponibles = count(array_filter($livres, fn($l) => (int) $l['disponibles'] <= 0));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Livres - BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/biblionet.css" rel="stylesheet">
</head>
<body>
<div class="app">

    <aside class="sidebar">
        <div class="logo">Biblio<span>Net</span></div>
        <div class="menu-titre">Gestion</div>
        <a href="/livres" class="actif">Livres</a>
        <a href="/adherents">Adhérents</a>
        <a href="/emprunts">Emprunts</a>
        <div class="menu-titre mt-3">Public</div>
        <a href="/catalogue">Catalogue</a>
        <div class="bas"><a href="/logout">Déconnexion</a></div>
    </aside>

    <main class="contenu">

        <div class="entete">
            <div>
                <h1>Livres</h1>
                <p class="sous-titre">Gérez le catalogue de la bibliothèque</p>
            </div>
            <a href="/livres/create" class="btn btn-primary">+ Ajouter un livre</a>
        </div>

        <?php if ($flash): ?>
            <div class="alert alert-<?= $e($flash['type']) ?>"><?= $e($flash['message']) ?></div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="carte stat bleu">
                    <div class="stat-label">Titres au catalogue</div>
                    <div class="stat-valeur"><?= count($livres) ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="carte stat">
                    <div class="stat-label">Exemplaires disponibles</div>
                    <div class="stat-valeur"><?= array_sum(array_column($livres, 'disponibles')) ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="carte stat rouge">
                    <div class="stat-label">Livres indisponibles</div>
                    <div class="stat-valeur"><?= $indisponibles ?></div>
                </div>
            </div>
        </div>

        <div class="carte p-0">
            <div class="carte-entete">
                <h2>Liste des livres</h2>
                <input type="search" id="recherche" class="form-control recherche" placeholder="Rechercher un livre...">
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="tableau">
                    <thead>
                        <tr>
                            <th>Titre</th><th>Auteur</th><th>Catégorie</th><th>Année</th>
                            <th>Exemplaires</th><th>Disponibles</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($livres as $l): ?>
                        <tr>
                            <td class="titre-livre"><?= $e($l['titre']) ?></td>
                            <td><?= $e($l['auteur']) ?></td>
                            <td><span class="etiquette"><?= $e($l['categorie']) ?></span></td>
                            <td><?= $e($l['annee']) ?></td>
                            <td><?= $e($l['nb_exemplaires']) ?></td>
                            <td>
                                <span class="pastille <?= $l['disponibles'] > 0 ? 'pastille-ok' : 'pastille-ko' ?>">
                                    <?= $l['disponibles'] > 0 ? $e($l['disponibles']) . ' dispo' : 'Indisponible' ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="/livres/edit/<?= $e($l['id']) ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                                    <form action="/livres/delete/<?= $e($l['id']) ?>" method="post"
                                          onsubmit="return confirm('Supprimer ce livre ?')">
                                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<script>
// Filtre la liste pendant la saisie
document.getElementById('recherche').addEventListener('input', function () {
    var q = this.value.toLowerCase();
    document.querySelectorAll('#tableau tbody tr').forEach(function (tr) {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
</body>
</html>