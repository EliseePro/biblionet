<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Adherent.php';
require_once __DIR__ . '/../models/Emprunt.php';

class AdherentController extends Controller
{
    private const AUTH_ACTIVE = true;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (self::AUTH_ACTIVE && empty($_SESSION['user_id'])) {
            $this->redirect('/login');
            exit;
        }
    }

    public function index(): void
    {
        $this->render('adherents/index', ['adherents' => Adherent::tous()]);
    }

    public function create(): void
    {
        $this->render('adherents/form', ['adherent' => null, 'old' => [], 'errors' => []]);
    }

    public function edit($id): void
    {
        $adherent = Adherent::trouver((int) $id);
        if ($adherent === null) {
            $this->message('danger', 'Adhérent introuvable.');
            $this->redirect('/adherents');
        }
        $this->render('adherents/form', ['adherent' => $adherent, 'old' => $adherent, 'errors' => []]);
    }

    public function enregistrer(?int $id = null): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->message('danger', 'Requête invalide.');
            $this->redirect('/adherents');
        }

        $data = [];
        foreach (['nom', 'prenom', 'classe', 'telephone'] as $field) {
            $data[$field] = trim((string) ($_POST[$field] ?? ''));
        }

        $errors = [];
        foreach (['nom', 'prenom', 'classe'] as $field) {
            if ($data[$field] === '') {
                $errors[$field] = 'Ce champ est obligatoire.';
            }
        }
        if ($this->longueur($data['nom']) > 100) {
            $errors['nom'] = 'Le nom ne peut pas dépasser 100 caractères.';
        }
        if ($this->longueur($data['prenom']) > 100) {
            $errors['prenom'] = 'Le prénom ne peut pas dépasser 100 caractères.';
        }
        if ($this->longueur($data['classe']) > 50) {
            $errors['classe'] = 'La classe ne peut pas dépasser 50 caractères.';
        }
        if ($this->longueur($data['telephone']) > 30) {
            $errors['telephone'] = 'Le téléphone ne peut pas dépasser 30 caractères.';
        }

        $adherent = $id === null ? null : Adherent::trouver($id);
        if ($id !== null && $adherent === null) {
            $this->message('danger', 'Adhérent introuvable.');
            $this->redirect('/adherents');
        }
        if ($errors !== []) {
            $this->render('adherents/form', [
                'adherent' => $adherent,
                'old' => $data,
                'errors' => $errors,
            ]);
            return;
        }

        $saved = $id === null ? Adherent::creer($data) : Adherent::modifier($id, $data);
        if (!$saved) {
            $this->message('danger', 'Enregistrement impossible. Vérifiez les données et réessayez.');
            $this->render('adherents/form', [
                'adherent' => $adherent,
                'old' => $data,
                'errors' => [],
            ]);
            return;
        }

        $this->message('success', $id === null ? 'Adhérent créé.' : 'Adhérent modifié.');
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

    public function supprimer($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->message('danger', 'Requête invalide.');
        } elseif (Adherent::supprimer((int) $id)) {
            $this->message('success', 'Adhérent supprimé.');
        } else {
            $this->message('danger', 'Suppression impossible : cet adhérent est introuvable ou possède des emprunts.');
        }
        $this->redirect('/adherents');
    }

    public function delete($id): void
    {
        $this->supprimer($id);
    }

    public function recherche($id): void
    {
        $adherent = Adherent::trouver((int) $id);
        if ($adherent === null) {
            $this->message('danger', 'Adhérent introuvable.');
            $this->redirect('/adherents');
        }
        $this->render('adherents/show', [
            'adherent' => $adherent,
            'emprunts' => Emprunt::empruntsParAdherent((int) $id),
        ]);
    }

    private function longueur(string $texte): int
    {
        return preg_match_all('/./us', $texte, $caracteres) === false
            ? PHP_INT_MAX
            : count($caracteres[0]);
    }
}
