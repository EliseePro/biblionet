<?php
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$modif = !empty($livre);
$action = $modif ? '/livres/update/' . (int) $livre['id'] : '/livres/store';
$champs = [
    'titre'          => ['Titre', 'text'],
    'auteur'         => ['Auteur', 'text'],
    'categorie'      => ['Catégorie', 'text'],
    'annee'          => ['Année', 'number'],
    'nb_exemplaires' => ['Nombre d\'exemplaires', 'number'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $modif ? 'Modifier' : 'Ajouter' ?> un livre - BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width: 600px;">

    <h1 class="h3 mb-3"><?= $modif ? 'Modifier' : 'Ajouter' ?> un livre</h1>

    <form action="<?= $e($action) ?>" method="post">
        <?php foreach ($champs as $nom => [$label, $type]): ?>
            <div class="mb-3">
                <label class="form-label"><?= $e($label) ?></label>
                <input type="<?= $type ?>" name="<?= $nom ?>" class="form-control"
                       value="<?= $e($old[$nom] ?? '') ?>">
                <?php if (isset($errors[$nom])): ?>
                    <div class="text-danger"><?= $e($errors[$nom]) ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <a href="/livres" class="btn btn-outline-secondary">Annuler</a>
        <button class="btn btn-primary"><?= $modif ? 'Enregistrer' : 'Ajouter' ?></button>
    </form>

</body>
</html>