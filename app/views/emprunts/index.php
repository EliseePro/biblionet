<?php
$e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$title = 'Emprunts';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <p class="dashboard-eyebrow mb-1">Gestion de la bibliothèque</p>
        <h1 class="h3 mb-0">Emprunts</h1>
    </div>
    <a href="<?= $e(app_url('/emprunts/retards')) ?>" class="btn btn-outline-warning">Voir les retards</a>
    <a href="<?= $e(app_url('/emprunts/create')) ?>" class="btn btn-primary">+ Nouvel emprunt</a>
</div>

    <?php if (empty($emprunts)): ?>
        <div class="card">
            <div class="card-body text-center text-muted py-5">Aucun emprunt enregistré.</div>
        </div>
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
                                    <form action="<?= $e(app_url('/emprunts/retour/' . (int) $emprunt['id'])) ?>" method="post"
                                          class="d-inline"
                                          onsubmit="return confirm('Confirmer le retour de ce livre ?')">
                                        <input type="hidden" name="csrf_token" value="<?= $e($csrf_token) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-success">Retourner</button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?= $e(app_url('/emprunts/delete/' . (int) $emprunt['id'])) ?>" method="post"
                                      class="d-inline" onsubmit="return confirm('Supprimer cet emprunt ?')">
                                    <input type="hidden" name="csrf_token" value="<?= $e($csrf_token) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
<?php require __DIR__ . '/../layouts/pagination.php'; ?>
