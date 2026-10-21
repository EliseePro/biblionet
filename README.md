# BiblioNet — Application de gestion de bibliothèque (MVC PHP/MySQL)

Mini-projet pédagogique : architecture **MVC sans framework**, PHP 8, MySQL/MariaDB, PDO, sessions.

## Prérequis

- PHP 8.x (extensions `pdo_mysql`, `mbstring`)
- MySQL ou MariaDB
- Serveur web avec réécriture d'URL (Apache + `mod_rewrite`, ou `php -S` en local)

## Installation locale

1. **Cloner** le dépôt et se placer dans le dossier du projet.

2. **Créer la base** et importer les données de test :
   ```bash
   mysql -u root -p < database/biblionet.sql
   ```
   (ou via phpMyAdmin : importer `database/biblionet.sql`)

3. **Configurer l'environnement** :
   ```bash
   cp .env.example .env
   ```
   Éditer `.env` avec vos identifiants MySQL (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`).

4. **Lancer en local** (document root = `public/`) :
   ```bash
   php -S localhost:8000 -t public
   ```
   Ouvrir http://localhost:8000

## Compte de démonstration

| Email                   | Mot de passe |
|-------------------------|--------------|
| admin@biblionet.local   | admin123     |

Le mot de passe est stocké avec `password_hash` / vérifié avec `password_verify`.

## Arborescence

```
biblionet/
├── app/
│   ├── controllers/   Auth, Livre, Adherent, Emprunt, Catalogue
│   ├── models/        User, Livre, Adherent, Emprunt
│   ├── views/         layouts, livres, adherents, emprunts, catalogue, auth
│   └── core/          Router, Controller, Database
├── config/config.php  charge .env + fonction connexion()
├── public/            ← seul dossier exposé (index.php, .htaccess, assets)
├── database/biblionet.sql
├── .env.example
└── README.md
```

## Fonctionnalités (J1–J2)

- **Socle MVC** : front controller (`public/index.php`), routeur, layout Bootstrap
- **Authentification** : connexion / déconnexion, session, mot de passe haché
- **CRUD Livres** : titre, auteur, catégorie, année, exemplaires + calcul de disponibilité
- **CRUD Adhérents** : nom, prénom, classe, téléphone
- **Emprunts** : enregistrement, retour, refus si aucun exemplaire disponible
- **Catalogue public** : liste + recherche titre/auteur, badge Disponible / Indisponible

## Mise en ligne (aperçu)

1. Créer la base et l'utilisateur MySQL chez l'hébergeur.
2. Importer `database/biblionet.sql`.
3. Transférer le projet (FTP ou git) ; pointer la racine du site vers `public/`.
4. Créer un `.env` **sur le serveur** (jamais sur GitHub) avec les identifiants de production.
5. `APP_ENV=production` pour masquer les erreurs PHP.

## Équipe

- Membre 1 — Socle & déploiement (Router, Database, Auth, mise en ligne)
- Membre 2 — Livres & catalogue
- Membre 3 — Adhérents & emprunts

## Licence

Projet pédagogique — usage libre dans le cadre du module.
