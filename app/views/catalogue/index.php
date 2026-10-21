<?php
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h1 class="h3 mb-0">Catalogue public</h1>
    <form method="get" action="/catalogue" class="d-flex gap-2" style="max-width: 360px; width: 100%;">
        <input type="search" name="q" class="form-control" placeholder="Titre ou auteur…"
               value="<?= $e($terme ?? '') ?>">
        <button class="btn btn-outline-primary">Rechercher</button>
    </form>
</div>

<?php if (($terme ?? '') !== ''): ?>
    <p class="text-muted">Résultats pour « <?= $e($terme) ?> » — <?= count($livres) ?> livre(s)</p>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Année</th>
                <th>Disponibilité</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($livres)): ?>
            <tr><td colspan="5" class="text-center text-muted py-4">Aucun livre trouvé.</td></tr>
        <?php else: ?>
            <?php foreach ($livres as $l): ?>
                <?php $dispo = (int) ($l['disponibles'] ?? 0) > 0; ?>
                <tr>
                    <td><?= $e($l['titre']) ?></td>
                    <td><?= $e($l['auteur']) ?></td>
                    <td><?= $e($l['categorie']) ?></td>
                    <td><?= $e($l['annee']) ?></td>
                    <td>
                        <?php if ($dispo): ?>
                            <span class="badge text-bg-success">Disponible</span>
                            <small class="text-muted">(<?= (int) $l['disponibles'] ?>)</small>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Indisponible</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
