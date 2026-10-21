<?php

require_once __DIR__ . '/../models/Livre.php';

class LivreController
{
    private const AUTH_ACTIVE = true;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (self::AUTH_ACTIVE && empty($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    public function index(): void
    {
        $this->render('livres/index', ['livres' => Livre::all()]);
    }

    public function create(): void
    {
        $this->render('livres/form', ['livre' => null, 'old' => [], 'errors' => []]);
    }

    public function edit($id): void
    {
        $livre = Livre::find((int) $id);
        if (!$livre) {
            $this->message('danger', 'Livre introuvable.');
            $this->redirect('/livres');
        }
        $this->render('livres/form', ['livre' => $livre, 'old' => $livre, 'errors' => []]);
    }

    public function store(): void
    {
        $this->enregistrer(null);
    }

    public function update($id): void
    {
        $this->enregistrer((int) $id);
    }

    // Suppression (uniquement en POST)
    public function delete($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && Livre::delete((int) $id)) {
            $this->message('success', 'Livre supprimé.');
        } else {
            $this->message('danger', 'Suppression impossible : ce livre a des emprunts.');
        }
        $this->redirect('/livres');
    }

    private function enregistrer(?int $id): void
    {
        $livre = $id ? Livre::find($id) : null;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($id && !$livre)) {
            $this->message('danger', 'Requête invalide.');
            $this->redirect('/livres');
        }

        $data = [];
        foreach (['titre', 'auteur', 'categorie', 'annee', 'nb_exemplaires'] as $champ) {
            $data[$champ] = trim($_POST[$champ] ?? '');
        }

        $errors = $this->valider($data);
        if ($errors) {
            $this->render('livres/form', ['livre' => $livre, 'old' => $data, 'errors' => $errors]);
            return;
        }

        if ($id) {
            Livre::update($id, $data);
            $this->message('success', 'Livre modifié.');
        } else {
            Livre::create($data);
            $this->message('success', 'Livre ajouté.');
        }
        $this->redirect('/livres');
    }

    private function valider(array $d): array
    {
        $errors = [];
        foreach (['titre', 'auteur', 'categorie'] as $champ) {
            if ($d[$champ] === '') {
                $errors[$champ] = 'Ce champ est obligatoire.';
            }
        }
        if (!ctype_digit($d['annee']) || $d['annee'] < 1000 || $d['annee'] > date('Y')) {
            $errors['annee'] = 'Année invalide.';
        }
        if (!ctype_digit($d['nb_exemplaires']) || $d['nb_exemplaires'] < 1) {
            $errors['nb_exemplaires'] = 'Au moins 1 exemplaire.';
        }
        return $errors;
    }

    private function render(string $vue, array $data = []): void
    {
        extract($data);
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        require __DIR__ . '/../views/' . $vue . '.php';
    }

    private function message(string $type, string $texte): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $texte];
    }

    private function redirect(string $chemin): void
    {
        header('Location: ' . $chemin);
        exit;
    }
}