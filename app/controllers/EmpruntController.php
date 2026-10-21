<?php

require_once __DIR__ . '/../models/Emprunt.php';
require_once __DIR__ . '/../models/Adherent.php';
require_once __DIR__ . '/../models/Livre.php';

class EmpruntController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function redirect(string $path): void
    {
        header('Location: ' . (function_exists('app_url') ? app_url($path) : $path));
        exit;
    }

    private function render(string $view, array $data = []): void
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    private function message(string $type, string $text): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $text];
    }

    private function formData(?array $emprunt = null, array $old = [], array $errors = []): array
    {
        return [
            'emprunt' => $emprunt,
            'old' => $old,
            'errors' => $errors,
            'adherents' => Adherent::tous(),
            'livres' => Livre::all(),
        ];
    }

    private function dateValide(string $date): bool
    {
        $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date);
        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }

    public function index(): void
    {
        $this->render('emprunts/index', ['emprunts' => Emprunt::tous()]);
    }

    public function create(): void
    {
        $this->render('emprunts/form', $this->formData(null, [
            'date_emprunt' => date('Y-m-d'),
        ]));
    }

    public function edit($id): void
    {
        $emprunt = Emprunt::trouver((int) $id);
        if ($emprunt === null) {
            $this->message('danger', 'Emprunt introuvable.');
            $this->redirect('/emprunts');
        }
        $this->render('emprunts/form', $this->formData($emprunt, $emprunt));
    }

    public function enregistrer(?int $id = null): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->message('danger', 'Requête invalide.');
            $this->redirect('/emprunts');
        }

        $data = [
            'adherent_id' => trim((string) ($_POST['adherent_id'] ?? '')),
            'livre_id' => trim((string) ($_POST['livre_id'] ?? '')),
            'date_emprunt' => trim((string) ($_POST['date_emprunt'] ?? '')),
        ];
        $emprunt = $id === null ? null : Emprunt::trouver($id);
        if ($id !== null && $emprunt === null) {
            $this->message('danger', 'Emprunt introuvable.');
            $this->redirect('/emprunts');
        }
        $errors = [];

        if (!ctype_digit($data['adherent_id']) || (int) $data['adherent_id'] < 1) {
            $errors['adherent_id'] = 'Sélectionnez un adhérent valide.';
        } elseif (Adherent::trouver((int) $data['adherent_id']) === null) {
            $errors['adherent_id'] = 'Cet adhérent n’existe pas.';
        }
        if (!ctype_digit($data['livre_id']) || (int) $data['livre_id'] < 1) {
            $errors['livre_id'] = 'Sélectionnez un livre valide.';
        } elseif (Livre::find((int) $data['livre_id']) === null) {
            $errors['livre_id'] = 'Ce livre n’existe pas.';
        } elseif ($id === null || $emprunt['date_retour'] === null) {
            if (!Emprunt::livreDisponible((int) $data['livre_id'], $id)) {
                $errors['livre_id'] = 'Aucun exemplaire de ce livre n’est disponible.';
            }
        }
        if (!$this->dateValide($data['date_emprunt'])) {
            $errors['date_emprunt'] = 'Saisissez une date d’emprunt valide.';
        } elseif ($data['date_emprunt'] > date('Y-m-d')) {
            $errors['date_emprunt'] = 'La date d’emprunt ne peut pas être dans le futur.';
        }

        if ($errors !== []) {
            $this->render('emprunts/form', $this->formData($emprunt, $data, $errors));
            return;
        }

        $saved = $id === null ? Emprunt::creer($data) : Emprunt::modifier($id, $data);
        if (!$saved) {
            $this->message(
                'danger',
                'Enregistrement impossible. Vérifiez l’adhérent, le livre et sa disponibilité, puis réessayez.'
            );
            $this->render('emprunts/form', $this->formData($emprunt, $data));
            return;
        }

        $this->message('success', $id === null ? 'Emprunt créé.' : 'Emprunt modifié.');
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

    public function supprimer($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->message('danger', 'Requête invalide.');
        } elseif (Emprunt::supprimer((int) $id)) {
            $this->message('success', 'Emprunt supprimé.');
        } else {
            $this->message('danger', 'Emprunt introuvable ou suppression impossible.');
        }
        $this->redirect('/emprunts');
    }

    public function delete($id): void
    {
        $this->supprimer($id);
    }

    public function marquerCommeRetourne($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->message('danger', 'Requête invalide.');
        } elseif (Emprunt::marquerCommeRetourne((int) $id)) {
            $this->message('success', 'Emprunt marqué comme retourné.');
        } else {
            $this->message('danger', 'Emprunt introuvable ou déjà retourné.');
        }
        $this->redirect('/emprunts');
    }
}
