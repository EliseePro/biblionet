<?php
$e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$title = 'Emprunts en retard';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <p class="dashboard-eyebrow mb-1">Gestion de la bibliothèque</p>
        <h1 class="h3 mb-0">Emprunts en retard</h1>
    </div>
    <a href="<?= $e(app_url('/emprunts')) ?>" class="btn btn-outline-secondary">Tous les emprunts</a>
</div>

<?php if (empty($emprunts)): ?>
    <div class="card">
        <div class="card-body text-center text-muted py-5">Aucun emprunt en retard pour le moment.</div>
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
                    <th>À rendre le</th>
                    <th>Retard</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($emprunts as $emprunt):
                    $dateLimite = (new DateTimeImmutable($emprunt['date_emprunt']))->modify('+14 days');
                    $joursRetard = $dateLimite->diff(new DateTimeImmutable('today'))->days;
                    ?>
                    <tr>
                        <td><?= $e($emprunt['adherent_nom'] . ' ' . $emprunt['adherent_prenom']) ?></td>
                        <td><?= $e($emprunt['adherent_classe']) ?></td>
                        <td><?= $e($emprunt['livre_titre']) ?></td>
                        <td><?= $e($emprunt['date_emprunt']) ?></td>
                        <td><?= $e($dateLimite->format('Y-m-d')) ?></td>
                        <td><span class="badge text-bg-danger"><?= (int) $joursRetard ?> jour(s)</span></td>
                        <td class="text-nowrap">
                            <a href="<?= $e(app_url('/emprunts/edit/' . (int) $emprunt['id'])) ?>"
                               class="btn btn-sm btn-outline-primary">Modifier</a>
                            <form action="<?= $e(app_url('/emprunts/retour/' . (int) $emprunt['id'])) ?>" method="post"
                                  class="d-inline"
                                  onsubmit="return confirm('Confirmer le retour de ce livre ?')">
                                <input type="hidden" name="csrf_token" value="<?= $e($csrf_token) ?>">
                                <button type="submit" class="btn btn-sm btn-outline-success">Retourner</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/../layouts/pagination.php'; ?>
