<?php
$e = fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$title = $adherent['prenom'] . ' ' . $adherent['nom'];
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <p class="dashboard-eyebrow mb-1">Fiche adhérent</p>
        <h1 class="h3 mb-0"><?= $e($title) ?></h1>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= $e(app_url('/adherents')) ?>" class="btn btn-outline-secondary">Retour à la liste</a>
        <a href="<?= $e(app_url('/adherents/edit/' . (int) $adherent['id'])) ?>" class="btn btn-primary">Modifier</a>
        <button onclick="exporterFicheEnCSV()" class="btn btn-outline-primary">Exporter en CSV</button>
    </div>
</div>
<section class="card">
    <div class="card-body p-4">
        <dl class="row mb-0">
            <dt class="col-sm-3">Nom</dt>
            <dd class="col-sm-9 info-adherent" data-label="Nom"><?= $e($adherent['nom']) ?></dd>
            <dt class="col-sm-3">Prénom</dt>
            <dd class="col-sm-9 info-adherent" data-label="Prénom"><?= $e($adherent['prenom']) ?></dd>
            <dt class="col-sm-3">Classe</dt>
            <dd class="col-sm-9 info-adherent" data-label="Classe"><?= $e($adherent['classe']) ?></dd>
            <dt class="col-sm-3">Téléphone</dt>
            <dd class="col-sm-9 info-adherent" data-label="Téléphone"><?= $e($adherent['telephone'] ?? '') ?></dd>
        </dl>
    </div>
</section>
<section class="mt-4">
    <h2 class="h5 mb-3">Historique des emprunts</h2>
    <?php if (empty($emprunts)): ?>
        <p class="text-muted">Aucun emprunt enregistré pour cet adhérent.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped" id="tableauEmprunts">
                <thead>
                    <tr><th>Livre</th><th>Emprunté le</th><th>Retourné le</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($emprunts as $emprunt): ?>
                        <tr>
                            <td><?= $e($emprunt['livre_titre']) ?></td>
                            <td><?= $e($emprunt['date_emprunt']) ?></td>
                            <td><?= $e($emprunt['date_retour'] ?? 'En cours') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    </section>
    <script>
    function exporterFicheEnCSV() {
        const csv = [];

        csv.push('"FICHE ADHÉRENT"');
        const infos = document.querySelectorAll('.info-adherent');
        infos.forEach(info => {
            const label = info.getAttribute('data-label');
            const valeur = info.innerText.replace(/"/g, '""');
            csv.push(`"${label}";"${valeur}"`);
        });

        csv.push('');

        const tableau = document.getElementById('tableauEmprunts');
        if (tableau) {
            csv.push('"HISTORIQUE DES EMPRUNTS"');
            const lignes = tableau.querySelectorAll('tr');

            lignes.forEach(ligne => {
                const ligneDonnees = [];
                const cellules = ligne.querySelectorAll('th, td');
                cellules.forEach(cellule => {
                    let texte = cellule.innerText.replace(/"/g, '""');
                    ligneDonnees.push(`"${texte}"`);
                });
                csv.push(ligneDonnees.join(';'));
            });
        } else {
            csv.push('"Aucun emprunt enregistré pour cet adhérent."');
        }

        const contenuCSV = "\uFEFF" + csv.join("\n");
        const blob = new Blob([contenuCSV], { type: 'text/csv;charset=utf-8;' });

        const nomAdherent = infos[1].innerText + '_' + infos[0].innerText;
        const nomFichier = `Fiche_${nomAdherent.replace(/\s+/g, '_')}.csv`;

        const lien = document.createElement("a");
        const url = URL.createObjectURL(blob);
        lien.setAttribute("href", url);
        lien.setAttribute("download", nomFichier);
        lien.style.visibility = 'hidden';
        document.body.appendChild(lien);
        lien.click();
        document.body.removeChild(lien);
    }
    </script>
