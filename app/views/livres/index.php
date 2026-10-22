<?php
/** @var array $livres */
$e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$title = 'Livres';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <p class="dashboard-eyebrow mb-1">Gestion de la bibliothèque</p>
        <h1 class="h3 mb-0">Livres</h1>
    </div>
    <a href="<?= $e(app_url('/livres/create')) ?>" class="btn btn-primary">+ Ajouter un livre</a>
</div>

<?php if (empty($livres)): ?>
    <div class="card">
        <div class="card-body text-center text-muted py-5">Aucun livre enregistré.</div>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Catégorie</th>
                    <th>Année</th>
                    <th>Exemplaires</th>
                    <th>Disponibles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livres as $l): ?>
                    <tr>
                        <td><?= $e($l['titre']) ?></td>
                        <td><?= $e($l['auteur']) ?></td>
                        <td><?= $e($l['categorie']) ?></td>
                        <td><?= $e($l['annee']) ?></td>
                        <td><?= $e($l['nb_exemplaires']) ?></td>
                        <td><?= $e($l['disponibles']) ?></td>
                        <td>
                            <a href="<?= $e(app_url('/livres/edit/' . (int) $l['id'])) ?>"
                               class="btn btn-sm btn-outline-primary">Modifier</a>
                            <form action="<?= $e(app_url('/livres/delete/' . (int) $l['id'])) ?>"
                                  method="post" class="d-inline"
                                  onsubmit="return confirm('Supprimer ce livre ?')">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
