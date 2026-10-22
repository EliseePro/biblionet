<?php
$e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$title = 'Adhérents';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <p class="dashboard-eyebrow mb-1">Gestion de la bibliothèque</p>
        <h1 class="h3 mb-0">Adhérents</h1>
    </div>
    <a href="<?= $e(app_url('/adherents/create')) ?>" class="btn btn-primary">+ Ajouter un adhérent</a>
</div>

    <?php if (empty($adherents)): ?>
        <div class="card">
            <div class="card-body text-center text-muted py-5">Aucun adhérent enregistré.</div>
        </div>
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
