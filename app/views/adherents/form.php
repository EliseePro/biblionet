<?php
<<<<<<< HEAD
$e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$modif = !empty($adherent);
$action = $modif ? app_url('/adherents/update/' . (int) $adherent['id']) : app_url('/adherents/store');
=======
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$modif = !empty($adherent);
$action = $modif ? '/adherents/update/' . (int) $adherent['id'] : '/adherents/store';
$champs = [
    'nom'       => ['Nom', 'text'],
    'prenom'    => ['Prénom', 'text'],
    'classe'    => ['Classe', 'text'],
    'telephone' => ['Téléphone', 'text'],
];
>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <meta name="viewport" content="width=device-width, initial-scale=1">
=======
>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
    <title><?= $modif ? 'Modifier' : 'Ajouter' ?> un adhérent - BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width: 600px;">
<<<<<<< HEAD
    <h1 class="h3 mb-3"><?= $modif ? 'Modifier' : 'Ajouter' ?> un adhérent</h1>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= $e($flash['type']) ?>"><?= $e($flash['message']) ?></div>
    <?php endif; ?>

    <form action="<?= $e($action) ?>" method="post">
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
=======

    <h1 class="h3 mb-3"><?= $modif ? 'Modifier' : 'Ajouter' ?> un adhérent</h1>

    <form action="<?= $e($action) ?>" method="post">
        <?php foreach ($champs as $nom => [$label, $type]): ?>
            <div class="mb-3">
                <label class="form-label"><?= $e($label) ?></label>
                <input type="<?= $type ?>" name="<?= $nom ?>" class="form-control <?= isset($errors[$nom]) ? 'is-invalid' : '' ?>"
                       value="<?= $e($old[$nom] ?? '') ?>">
                <?php if (isset($errors[$nom])): ?>
                    <div class="invalid-feedback"><?= $e($errors[$nom]) ?></div>
>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

<<<<<<< HEAD
        <a href="<?= $e(app_url('/adherents')) ?>" class="btn btn-outline-secondary">Annuler</a>
        <button type="submit" class="btn btn-primary"><?= $modif ? 'Enregistrer' : 'Ajouter' ?></button>
    </form>
=======
        <a href="/adherents" class="btn btn-outline-secondary">Annuler</a>
        <button class="btn btn-primary"><?= $modif ? 'Enregistrer' : 'Ajouter' ?></button>
    </form>

>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
</body>
</html>
