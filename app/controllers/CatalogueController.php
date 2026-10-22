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
        $pagination = $this->paginationData(
            Livre::compter($terme !== '' ? $terme : null),
            $this->requestedPage()
        );
        $this->render('catalogue/index', [
            'title'  => 'Catalogue',
            'livres' => Livre::pagines(
                $pagination['per_page'],
                $pagination['offset'],
                $terme !== '' ? $terme : null
            ),
            'terme'  => $terme,
            'pagination' => $pagination,
            'paginationPath' => '/catalogue',
            'paginationQuery' => $terme !== '' ? ['q' => $terme] : [],
        ]);
    }
}
