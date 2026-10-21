<?php

require_once __DIR__ . '/../models/Adherent.php';

class AdherentController
{
    private const AUTH_ACTIVE = false; // passer à true quand l'authentification de Membre 1 est prête

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (self::AUTH_ACTIVE && empty($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    private function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    private function message(string $type, string $text): void
    {
        $_SESSION['flash_message'] = ['type' => $type, 'text' => $text];
    }

    // Liste des adhérents
    public function index(): void
    {
        $this->render('adherents/index', ['adherents' => Adherent::tous()]);
    }

    // Formulaire d'ajout (vide)
    public function create(): void
    {
        $this->render('adherents/form', ['adherent' => null, 'old' => [], 'errors' => []]);
    }

    // Formulaire de modification (pré-rempli)
    public function edit($id): void
    {
        $adherent = Adherent::trouver((int) $id);
        if (!$adherent) {
            $this->message('danger', 'Adhérent introuvable.');
            $this->redirect('/adherents');
        }
        $this->render('adherents/form', ['adherent' => $adherent, 'old' => $adherent, 'errors' => []]);
    }

    public function enregistrer(?int $id = null): void
    {
        $data = [
            'nom' => $_POST['nom'] ?? '',
            'prenom' => $_POST['prenom'] ?? '',
            'classe' => $_POST['classe'] ?? '',
            'telephone' => $_POST['telephone'] ?? '',
        ];

        // Validation simple
        $errors = [];
        foreach ($data as $key => $value) {
            if (empty($value)) {
                $errors[$key] = ucfirst($key) . ' est requis.';
            }
        }

        if (!empty($errors)) {
            $this->render('adherents/form', ['adherent' => null, 'old' => $data, 'errors' => $errors]);
            return;
        }

        if ($id === null) {
            // Création
            if (Adherent::creer($data)) {
                $this->message('success', 'Adhérent créé.');
                $this->redirect('/adherents');
            } else {
                $this->message('danger', 'Erreur lors de la création.');
                $this->render('adherents/form', ['adherent' => null, 'old' => $data, 'errors' => []]);
            }
        } else {
            // Modification
            if (Adherent::modifier($id, $data)) {
                $this->message('success', 'Adhérent modifié.');
                $this->redirect('/adherents');
            } else {
                $this->message('danger', 'Erreur lors de la modification.');
                $this->render('adherents/form', ['adherent' => Adherent::trouver($id), 'old' => $data, 'errors' => []]);
            }
        }
    }

    public function supprimer($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Adherent::supprimer((int) $id)) {
                $this->message('success', 'Adhérent supprimé.');
            } else {
                $this->message('danger', 'Suppression impossible : cet adhérent a des emprunts.');
            }
        }
        $this->redirect('/adherents');
    }

    public function store(): void
    {
        $this->enregistrer();
    }

    public function update($id): void
    {
        $this->enregistrer((int) $id);
    }

    public function recherche($id): void
    {
        $adherent = Adherent::trouver((int) $id);
        if (!$adherent) {
            $this->message('danger', 'Adhérent introuvable.');
            $this->redirect('/adherents');
        }
        $emprunts = Emprunt::empruntsParAdherent((int) $id);
        $this->render('adherents/show', ['adherent' => $adherent, 'emprunts' => $emprunts]);
    }
 }