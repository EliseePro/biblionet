<?php
/** @var array|null $livre */
/** @var array $old */
/** @var array $errors */
$e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$modif = !empty($livre);
$title = ($modif ? 'Modifier' : 'Ajouter') . ' un livre';
$action = app_url($modif ? '/livres/update/' . (int) $livre['id'] : '/livres/store');
$champs = [
    'titre' => ['Titre', 'text'],
    'auteur' => ['Auteur', 'text'],
    'categorie' => ['Catégorie', 'text'],
    'annee' => ['Année', 'number'],
    'nb_exemplaires' => ['Nombre d\'exemplaires', 'number'],
];
?>
<div class="page page-form">
    <h1 class="h3 mb-4"><?= $e($title) ?></h1>
    <div class="card">
        <div class="card-body p-4">
            <form action="<?= $e($action) ?>" method="post">
                <?php foreach ($champs as $nom => [$label, $type]): ?>
                    <div class="mb-3">
                        <label class="form-label" for="<?= $e($nom) ?>"><?= $e($label) ?></label>
                        <input id="<?= $e($nom) ?>" type="<?= $e($type) ?>" name="<?= $e($nom) ?>"
                               class="form-control<?= isset($errors[$nom]) ? ' is-invalid' : '' ?>"
                               value="<?= $e($old[$nom] ?? '') ?>"
                               <?= $nom === 'annee' ? 'min="1000" max="' . date('Y') . '"' : '' ?>
                               <?= $nom === 'nb_exemplaires' ? 'min="1"' : '' ?>
                               required>
                        <?php if (isset($errors[$nom])): ?>
                            <div class="invalid-feedback"><?= $e($errors[$nom]) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex gap-2">
                    <a href="<?= $e(app_url('/livres')) ?>" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary"><?= $modif ? 'Enregistrer' : 'Ajouter' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
