<?php
$e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$modif = !empty($adherent);
$title = ($modif ? 'Modifier' : 'Ajouter') . ' un adhérent';
$action = app_url($modif ? '/adherents/update/' . (int) $adherent['id'] : '/adherents/store');
?>
<div class="page page-form">
    <h1 class="h3 mb-4"><?= $e($title) ?></h1>
    <div class="card">
        <div class="card-body p-4">
            <form action="<?= $e($action) ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= $e($csrf_token) ?>">
                <?php foreach ([
                    'nom' => ['Nom', 'text', true, 100],
                    'prenom' => ['Prénom', 'text', true, 100],
                    'classe' => ['Classe', 'text', true, 50],
                    'telephone' => ['Téléphone', 'tel', false, 30],
                ] as $name => [$label, $type, $required, $maxLength]): ?>
                    <div class="mb-3">
                        <label for="<?= $e($name) ?>" class="form-label"><?= $e($label) ?></label>
                        <input id="<?= $e($name) ?>" type="<?= $e($type) ?>" name="<?= $e($name) ?>"
                               class="form-control<?= isset($errors[$name]) ? ' is-invalid' : '' ?>"
                               maxlength="<?= (int) $maxLength ?>"
                               value="<?= $e($old[$name] ?? '') ?>"<?= $required ? ' required' : '' ?>>
                        <?php if (isset($errors[$name])): ?>
                            <div class="invalid-feedback"><?= $e($errors[$name]) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex gap-2">
                    <a href="<?= $e(app_url('/adherents')) ?>" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary"><?= $modif ? 'Enregistrer' : 'Ajouter' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
