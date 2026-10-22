<?php
$e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$modif = !empty($emprunt);
$title = ($modif ? 'Modifier' : 'Enregistrer') . ' un emprunt';
$action = app_url($modif ? '/emprunts/update/' . (int) $emprunt['id'] : '/emprunts/store');
$selectedAdherent = (string) ($old['adherent_id'] ?? '');
$selectedLivre = (string) ($old['livre_id'] ?? '');
$aUnLivreDisponible = false;
foreach ($livres as $livre) {
    if ((int) $livre['disponibles'] > 0) {
        $aUnLivreDisponible = true;
        break;
    }
}
$peutEnregistrer = !empty($adherents) && ($modif || $aUnLivreDisponible);
?>
<div class="page page-form page-form-wide">
    <h1 class="h3 mb-4"><?= $e($title) ?></h1>

    <?php if (empty($adherents)): ?>
        <div class="alert alert-warning">Ajoutez d’abord un adhérent avant de créer un emprunt.</div>
    <?php endif; ?>
    <?php if (empty($livres)): ?>
        <div class="alert alert-warning">Aucun livre n’est enregistré dans le catalogue.</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-4">
            <form action="<?= $e($action) ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= $e($csrf_token) ?>">
                <div class="mb-3">
                    <label for="adherent_id" class="form-label">Adhérent</label>
                    <select id="adherent_id" name="adherent_id"
                            class="form-select<?= isset($errors['adherent_id']) ? ' is-invalid' : '' ?>" required>
                        <option value="">Choisir un adhérent</option>
                        <?php foreach ($adherents as $a): ?>
                            <option value="<?= (int) $a['id'] ?>"
                                <?= $selectedAdherent === (string) $a['id'] ? 'selected' : '' ?>>
                                <?= $e($a['nom'] . ' ' . $a['prenom'] . ' — ' . $a['classe']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['adherent_id'])): ?>
                        <div class="invalid-feedback"><?= $e($errors['adherent_id']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="livre_id" class="form-label">Livre</label>
                    <select id="livre_id" name="livre_id"
                            class="form-select<?= isset($errors['livre_id']) ? ' is-invalid' : '' ?>" required>
                        <option value="">Choisir un livre</option>
                        <?php foreach ($livres as $l):
                            $disponibles = (int) $l['disponibles'];
                            $estSelectionne = $selectedLivre === (string) $l['id'];
                            $indisponible = $disponibles < 1 && !$estSelectionne;
                            ?>
                            <option value="<?= (int) $l['id'] ?>" <?= $estSelectionne ? 'selected' : '' ?>
                                <?= $indisponible ? 'disabled' : '' ?>>
                                <?= $e($l['titre']) ?> (<?= $indisponible ? 'indisponible' : $disponibles . ' disponible(s)' ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['livre_id'])): ?>
                        <div class="invalid-feedback"><?= $e($errors['livre_id']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="date_emprunt" class="form-label">Date d’emprunt</label>
                    <input id="date_emprunt" type="date" name="date_emprunt"
                           class="form-control<?= isset($errors['date_emprunt']) ? ' is-invalid' : '' ?>"
                           value="<?= $e($old['date_emprunt'] ?? date('Y-m-d')) ?>"
                           max="<?= $e(date('Y-m-d')) ?>" required>
                    <?php if (isset($errors['date_emprunt'])): ?>
                        <div class="invalid-feedback"><?= $e($errors['date_emprunt']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="d-flex gap-2">
                    <a href="<?= $e(app_url('/emprunts')) ?>" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary"<?= $peutEnregistrer ? '' : ' disabled' ?>>
                        <?= $modif ? 'Enregistrer' : 'Ajouter' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
