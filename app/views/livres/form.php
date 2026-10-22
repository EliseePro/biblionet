<?php
/** @var array|null $livre */
/** @var array $old */
/** @var array $errors */
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$modif = !empty($livre);
$title = ($modif ? 'Modifier' : 'Ajouter') . ' un livre';
$action = $modif ? '/livres/update/' . (int) $livre['id'] : '/livres/store';
$champs = [
    'titre'          => ['Titre', 'text'],
    'auteur'         => ['Auteur', 'text'],
    'categorie'      => ['Catégorie', 'text'],
    'annee'          => ['Année', 'number'],
    'nb_exemplaires' => ['Nombre d\'exemplaires', 'number'],
];
?>
<<<<<<< HEAD
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $modif ? 'Modifier' : 'Ajouter' ?> un livre - BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/biblionet.css" rel="stylesheet">
</head>
<body>
<div class="page" style="max-width: 600px;">

    <h1 class="h3 mb-4"><?= $modif ? 'Modifier' : 'Ajouter' ?> un livre</h1>

    <div class="carte">
        <form action="<?= $e($action) ?>" method="post">
            <?php foreach ($champs as $nom => [$label, $type]): ?>
                <div class="mb-3">
                    <label class="form-label"><?= $e($label) ?></label>
                    <input type="<?= $type ?>" name="<?= $nom ?>" class="form-control"
                           value="<?= $e($old[$nom] ?? '') ?>">
                    <?php if (isset($errors[$nom])): ?>
                        <div class="text-danger small mt-1"><?= $e($errors[$nom]) ?></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div class="d-flex justify-content-between">
                <a href="/livres" class="btn btn-outline-secondary">Annuler</a>
                <button class="btn btn-primary"><?= $modif ? 'Enregistrer' : 'Ajouter' ?></button>
            </div>
        </form>
    </div>

</div>
</body>
</html>
=======
<div class="page page-form">
    <h1 class="h3 mb-4"><?= $e($title) ?></h1>
    <div class="card">
        <div class="card-body p-4">
            <form action="<?= $e(app_url($action)) ?>" method="post">
                <?php foreach ($champs as $nom => [$label, $type]): ?>
                    <div class="mb-3">
                        <label class="form-label" for="<?= $e($nom) ?>"><?= $e($label) ?></label>
                        <input id="<?= $e($nom) ?>" type="<?= $e($type) ?>" name="<?= $e($nom) ?>" class="form-control"
                               value="<?= $e($old[$nom] ?? '') ?>">
                        <?php if (isset($errors[$nom])): ?>
                            <div class="text-danger"><?= $e($errors[$nom]) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex gap-2">
                    <a href="<?= $e(app_url('/livres')) ?>" class="btn btn-outline-secondary">Annuler</a>
                    <button class="btn btn-primary"><?= $modif ? 'Enregistrer' : 'Ajouter' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
>>>>>>> 13da819 (Réglage des erreurs)
