<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Livre.php';

class CatalogueController extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index(): void
    {
        $terme = trim((string) ($_GET['q'] ?? ''));
        $livres = $terme !== '' ? Livre::search($terme) : Livre::all();
        $this->render('catalogue/index', [
            'title'  => 'Catalogue',
            'livres' => $livres,
            'terme'  => $terme,
        ]);
    }
}
