<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Adherent.php';
require_once __DIR__ . '/../models/Emprunt.php';
require_once __DIR__ . '/../models/Livre.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $this->render('dashboard/index', [
            'title' => 'Tableau de bord',
            'adherentsTotal' => Adherent::compter(),
            'livres' => Livre::statistiques(),
            'emprunts' => Emprunt::statistiques(),
            'empruntsRecents' => Emprunt::recents(),
        ]);
    }
}
