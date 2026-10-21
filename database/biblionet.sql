CREATE DATABASE bibliotheque;
USE bibliotheque;

DROP TABLE IF EXISTS emprunts;
DROP TABLE IF EXISTS adherents;
DROP TABLE IF EXISTS livres;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom       VARCHAR(100) NOT NULL,
    email     VARCHAR(150) NOT NULL,
    password  VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE livres (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titre           VARCHAR(200) NOT NULL,
    auteur          VARCHAR(150) NOT NULL,
    categorie       VARCHAR(100) NOT NULL,
    annee           SMALLINT UNSIGNED NULL,
    nb_exemplaires  INT UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    KEY idx_livres_titre (titre),
    KEY idx_livres_auteur (auteur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE adherents (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom        VARCHAR(100) NOT NULL,
    prenom     VARCHAR(100) NOT NULL,
    classe     VARCHAR(50)  NOT NULL,
    telephone  VARCHAR(30)  NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE emprunts (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    livre_id       INT UNSIGNED NOT NULL,
    adherent_id    INT UNSIGNED NOT NULL,
    date_emprunt   DATE NOT NULL,
    date_retour    DATE NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY idx_emprunts_livre (livre_id),
    KEY idx_emprunts_adherent (adherent_id),
    CONSTRAINT fk_emprunts_livre
        FOREIGN KEY (livre_id) REFERENCES livres (id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_emprunts_adherent
        FOREIGN KEY (adherent_id) REFERENCES adherents (id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Compte bibliothécaire de démonstration
--   email : admin@biblionet.local
--   mot de passe : admin123   (à changer en production)
INSERT INTO users (nom, email, password) VALUES
('Bibliothécaire', 'admin@biblionet.local',
 '$2y$10$Pe0Jj9aaGu6dWicKSTwi0OvmVSGLSxLJfRLCHLn7g5xReboq02Ta.');

INSERT INTO livres (titre, auteur, categorie, annee, nb_exemplaires) VALUES
('Le Petit Prince',                    'Antoine de Saint-Exupéry', 'Roman',        1943, 3),
('Les Misérables',                     'Victor Hugo',              'Roman',        1862, 2),
('L''Étranger',                        'Albert Camus',             'Roman',        1942, 2),
('Une si longue lettre',               'Mariama Bâ',               'Roman',        1979, 3),
('Things Fall Apart',                  'Chinua Achebe',            'Roman',        1958, 2),
('Germinal',                           'Émile Zola',               'Roman',        1885, 1),
('Candide',                            'Voltaire',                 'Philosophie',  1759, 2),
('Le Contrat social',                  'Jean-Jacques Rousseau',    'Philosophie',  1762, 1),
('Cahier d''un retour au pays natal',  'Aimé Césaire',             'Poésie',       1939, 1),
('Les Fleurs du mal',                  'Charles Baudelaire',       'Poésie',       1857, 2),
('Introduction à PHP 8',               'Collectif',                'Informatique', 2021, 4),
('MySQL : conception et requêtes',     'Collectif',                'Informatique', 2020, 3),
('Réseaux informatiques',              'Andrew Tanenbaum',         'Informatique', 2011, 2),
('Algorithmique et programmation',     'Thomas H. Cormen',         'Informatique', 2010, 2),
('Histoire générale de l''Afrique',    'Joseph Ki-Zerbo',          'Histoire',     1972, 1),
('Sapiens : une brève histoire',       'Yuval Noah Harari',        'Histoire',     2011, 2),
('Mathématiques Terminale S',          'Collectif',                'Scolaire',     2018, 5),
('Physique-Chimie Seconde',            'Collectif',                'Scolaire',     2019, 4);

-- 12 adhérents
INSERT INTO adherents (nom, prenom, classe, telephone) VALUES
('Mba',        'Clarisse',  'Terminale A', '060101010'),
('Obame',      'Junior',    'Terminale A', '060202020'),
('Nzamba',     'Sarah',     'Terminale C', '060303030'),
('Moussavou',  'Kevin',     'Première S',  '060404040'),
('Ndong',      'Ruth',      'Première S',  '060505050'),
('Ondo',       'Patrick',   'Seconde B',   '060606060'),
('Bongo',      'Estelle',   'Seconde B',   '060707070'),
('Mintsa',     'Yann',      'Troisième',   '060808080'),
('Ekomi',      'Grâce',     'Troisième',   '060909090'),
('Ayingone',   'Brice',     'Terminale C', '061010101'),
('Nguema',     'Lydie',     'Première L',  '061111111'),
('Mouity',     'Fabrice',   'Seconde A',   '061212121');

-- Emprunts (dates relatives à aujourd'hui pour toujours avoir des cas parlants)
INSERT INTO emprunts (livre_id, adherent_id, date_emprunt, date_retour) VALUES
-- Déjà rendus
(1,  1,  DATE_SUB(CURDATE(), INTERVAL 40 DAY), DATE_SUB(CURDATE(), INTERVAL 30 DAY)),
(4,  3,  DATE_SUB(CURDATE(), INTERVAL 35 DAY), DATE_SUB(CURDATE(), INTERVAL 25 DAY)),
(11, 4,  DATE_SUB(CURDATE(), INTERVAL 28 DAY), DATE_SUB(CURDATE(), INTERVAL 20 DAY)),
-- En cours, dans les délais
(2,  2,  DATE_SUB(CURDATE(), INTERVAL 3 DAY),  NULL),
(7,  5,  DATE_SUB(CURDATE(), INTERVAL 5 DAY),  NULL),
(12, 6,  DATE_SUB(CURDATE(), INTERVAL 2 DAY),  NULL),
(17, 7,  DATE_SUB(CURDATE(), INTERVAL 1 DAY),  NULL),
-- En cours, en retard (plus de 14 jours)
(6,  8,  DATE_SUB(CURDATE(), INTERVAL 20 DAY), NULL),
(8,  9,  DATE_SUB(CURDATE(), INTERVAL 18 DAY), NULL),
(1,  10, DATE_SUB(CURDATE(), INTERVAL 16 DAY), NULL);