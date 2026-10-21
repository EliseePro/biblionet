<?php

require_once __DIR__ . '/../models/Emprunt.php';

class EmpruntController
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

    // Liste des emprunts
    public function index(): void
    {
        $this->render('emprunts/index', ['emprunts' => Emprunt::tous()]);
    }

    // Formulaire d'ajout (vide)
    public function create(): void
    {
        $this->render('emprunts/form', ['emprunt' => null, 'old' => [], 'errors' => []]);
    }

    // Formulaire de modification (pré-rempli)
    public function edit($id): void
    {
        $emprunt = Emprunt::trouver((int) $id);
        if (!$emprunt) {
            $this->message('danger', 'Emprunt introuvable.');
            $this->redirect('/emprunts');
        }
        $this->render('emprunts/form', ['emprunt' => $emprunt, 'old' => $emprunt, 'errors' => []]);
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
            $this->render('emprunts/form', ['emprunt' => null, 'old' => $data, 'errors' => $errors]);
            return;
        }

        if ($id === null) {
            // Création
            if (Emprunt::creer($data)) {
                $this->message('success', 'Emprunt créé.');
                $this->redirect('/emprunts');
            } else {
                $this->message('danger', 'Erreur lors de la création.');
                $this->render('emprunts/form', ['emprunt' => null, 'old' => $data, 'errors' => []]);
            }
        } else {
            // Modification
            if (Emprunt::modifier($id, $data)) {
                $this->message('success', 'Emprunt modifié.');
                $this->redirect('/emprunts');
            } else {
                $this->message('danger', 'Erreur lors de la modification.');
                $this->render('emprunts/form', ['emprunt' => Emprunt::trouver($id), 'old' => $data, 'errors' => []]);
            }
        }
    }

    public function supprimer($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Emprunt::supprimer((int) $id)) {
                $this->message('success', 'Emprunt supprimé.');
            } else {
                $this->message('danger', 'Suppression impossible : cet adhérent a des emprunts.');
            }
        }
        $this->redirect('/emprunts');
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
    public function marquerCommeRetourne($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (Emprunt::marquerCommeRetourne((int) $id)) {
                $this->message('success', 'Emprunt marqué comme retourné.');
            } else {
                $this->message('danger', 'Erreur lors de la mise à jour.');
            }
        }
        $this->redirect('/emprunts');
    }

    
 }