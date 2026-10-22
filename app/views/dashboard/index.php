<?php $e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>
<div class="dashboard-header d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <p class="dashboard-eyebrow mb-1">Espace bibliothécaire</p>
        <h1 class="h2 mb-1">Tableau de bord</h1>
        <p class="text-muted mb-0">Vue d’ensemble de l’activité de la bibliothèque.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= $e(app_url('/emprunts/create')) ?>" class="btn btn-primary">Nouvel emprunt</a>
        <a href="<?= $e(app_url('/livres/create')) ?>" class="btn btn-outline-primary">Ajouter un livre</a>
    </div>
</div>

<section class="row g-3 mb-4" aria-label="Statistiques de la bibliothèque">
    <div class="col-12 col-sm-6 col-xl">
        <article class="card dashboard-stat h-100">
            <div class="card-body">
                <p class="dashboard-stat-label">Livres au catalogue</p>
                <p class="dashboard-stat-value"><?= (int) $livres['titres'] ?></p>
                <p class="dashboard-stat-note">Titres différents</p>
            </div>
        </article>
    </div>
    <div class="col-12 col-sm-6 col-xl">
        <article class="card dashboard-stat dashboard-stat-blue h-100">
            <div class="card-body">
                <p class="dashboard-stat-label">Exemplaires</p>
                <p class="dashboard-stat-value"><?= (int) $livres['exemplaires'] ?></p>
                <p class="dashboard-stat-note"><?= (int) $livres['disponibles'] ?> disponibles</p>
            </div>
        </article>
    </div>
    <div class="col-12 col-sm-6 col-xl">
        <article class="card dashboard-stat dashboard-stat-green h-100">
            <div class="card-body">
                <p class="dashboard-stat-label">Adhérents</p>
                <p class="dashboard-stat-value"><?= (int) $adherentsTotal ?></p>
                <p class="dashboard-stat-note">Inscrits à la bibliothèque</p>
            </div>
        </article>
    </div>
    <div class="col-12 col-sm-6 col-xl">
        <article class="card dashboard-stat dashboard-stat-blue h-100">
            <div class="card-body">
                <p class="dashboard-stat-label">Emprunts en cours</p>
                <p class="dashboard-stat-value"><?= (int) $emprunts['en_cours'] ?></p>
                <p class="dashboard-stat-note"><?= (int) $emprunts['total'] ?> enregistrés au total</p>
            </div>
        </article>
    </div>
    <div class="col-12 col-sm-6 col-xl">
        <article class="card dashboard-stat dashboard-stat-red h-100">
            <div class="card-body">
                <p class="dashboard-stat-label">En retard</p>
                <p class="dashboard-stat-value"><?= (int) $emprunts['en_retard'] ?></p>
                <p class="dashboard-stat-note">Non retournés depuis plus de 14 jours</p>
            </div>
        </article>
    </div>
</section>

<section class="card dashboard-recent">
    <div class="card-header d-flex justify-content-between align-items-center gap-3 py-3 px-4">
        <div>
            <h2 class="h5 mb-1">Emprunts récents</h2>
            <p class="text-muted small mb-0">Les cinq dernières opérations enregistrées.</p>
        </div>
        <a href="<?= $e(app_url('/emprunts')) ?>" class="btn btn-sm btn-outline-primary">Voir tous les emprunts</a>
    </div>
    <?php if (empty($empruntsRecents)): ?>
        <div class="card-body text-center text-muted py-5">
            <p class="mb-0">Aucun emprunt n’a encore été enregistré.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive border-0 rounded-0">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Adhérent</th>
                        <th scope="col">Livre</th>
                        <th scope="col">État</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empruntsRecents as $emprunt): ?>
                        <tr>
                            <td><?= $e($emprunt['date_emprunt']) ?></td>
                            <td><?= $e($emprunt['adherent_prenom'] . ' ' . $emprunt['adherent_nom']) ?></td>
                            <td><?= $e($emprunt['livre_titre']) ?></td>
                            <td>
                                <?php if ($emprunt['date_retour'] === null): ?>
                                    <span class="badge text-bg-warning">En cours</span>
                                <?php else: ?>
                                    <span class="badge text-bg-success">Retourné</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
