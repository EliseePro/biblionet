<?php
<<<<<<< HEAD
$e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$modif = !empty($emprunt);
$action = $modif ? app_url('/emprunts/update/' . (int) $emprunt['id']) : app_url('/emprunts/store');
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
=======
$e = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$modif = !empty($emprunt);
$action = $modif ? '/emprunts/update/' . (int) $emprunt['id'] : '/emprunts/store';
>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $modif ? 'Modifier' : 'Enregistrer' ?> un emprunt - BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width: 700px;">
    <h1 class="h3 mb-3"><?= $modif ? 'Modifier' : 'Enregistrer' ?> un emprunt</h1>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= $e($flash['type']) ?>"><?= $e($flash['message']) ?></div>
    <?php endif; ?>

    <?php if (empty($adherents)): ?>
        <div class="alert alert-warning">Ajoutez d’abord un adhérent avant de créer un emprunt.</div>
    <?php endif; ?>
    <?php if (empty($livres)): ?>
        <div class="alert alert-warning">Aucun livre n’est enregistré dans le catalogue.</div>
    <?php endif; ?>

    <form action="<?= $e($action) ?>" method="post">
        <div class="mb-3">
            <label for="adherent_id" class="form-label">Adhérent</label>
            <select id="adherent_id" name="adherent_id"
                    class="form-select<?= isset($errors['adherent_id']) ? ' is-invalid' : '' ?>" required>
                <option value="">Choisir un adhérent</option>
                <?php foreach ($adherents as $a): ?>
                    <option value="<?= (int) $a['id'] ?>"
                        <?= $selectedAdherent === (string) $a['id'] ? 'selected' : '' ?>>
=======
    <title><?= $modif ? 'Modifier' : 'Nouvel' ?> emprunt - BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4" style="max-width: 600px;">

    <h1 class="h3 mb-3"><?= $modif ? 'Modifier' : 'Enregistrer' ?> un emprunt</h1>

    <form action="<?= $e($action) ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Livre</label>
            <select name="livre_id" class="form-select <?= isset($errors['livre_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">— Choisir —</option>
                <?php foreach ($livres as $l): ?>
                    <?php
                    $sel = (int) ($old['livre_id'] ?? 0) === (int) $l['id'] ? 'selected' : '';
                    $dispo = (int) ($l['disponibles'] ?? 0);
                    $label = $l['titre'] . ' (' . $dispo . ' dispo.)';
                    ?>
                    <option value="<?= (int) $l['id'] ?>" <?= $sel ?>><?= $e($label) ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['livre_id'])): ?>
                <div class="invalid-feedback"><?= $e($errors['livre_id']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Adhérent</label>
            <select name="adherent_id" class="form-select <?= isset($errors['adherent_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">— Choisir —</option>
                <?php foreach ($adherents as $a): ?>
                    <?php $sel = (int) ($old['adherent_id'] ?? 0) === (int) $a['id'] ? 'selected' : ''; ?>
                    <option value="<?= (int) $a['id'] ?>" <?= $sel ?>>
>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
                        <?= $e($a['nom'] . ' ' . $a['prenom'] . ' — ' . $a['classe']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['adherent_id'])): ?>
                <div class="invalid-feedback"><?= $e($errors['adherent_id']) ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
<<<<<<< HEAD
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
                   value="<?= $e($old['date_emprunt'] ?? date('Y-m-d')) ?>" max="<?= $e(date('Y-m-d')) ?>" required>
            <?php if (isset($errors['date_emprunt'])): ?>
                <div class="invalid-feedback"><?= $e($errors['date_emprunt']) ?></div>
            <?php endif; ?>
        </div>

        <a href="<?= $e(app_url('/emprunts')) ?>" class="btn btn-outline-secondary">Annuler</a>
        <button type="submit" class="btn btn-primary"<?= $peutEnregistrer ? '' : ' disabled' ?>>
            <?= $modif ? 'Enregistrer' : 'Ajouter' ?>
        </button>
    </form>
=======
            <label class="form-label">Date d'emprunt</label>
            <input type="date" name="date_emprunt" class="form-control"
                   value="<?= $e($old['date_emprunt'] ?? date('Y-m-d')) ?>">
            <?php if (isset($errors['date_emprunt'])): ?>
                <div class="text-danger"><?= $e($errors['date_emprunt']) ?></div>
            <?php endif; ?>
        </div>

        <?php if ($modif): ?>
        <div class="mb-3">
            <label class="form-label">Date de retour (vide = encore emprunté)</label>
            <input type="date" name="date_retour" class="form-control"
                   value="<?= $e($old['date_retour'] ?? '') ?>">
        </div>
        <?php endif; ?>

        <a href="/emprunts" class="btn btn-outline-secondary">Annuler</a>
        <button class="btn btn-primary"><?= $modif ? 'Enregistrer' : 'Emprunter' ?></button>
    </form>

>>>>>>> affb9a9 (j'ai fait des motifs un peu partout 🙌)
</body>
</html>
