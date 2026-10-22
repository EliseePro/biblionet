<?php

declare(strict_types=1);

session_start();

define('ROOT', dirname(__DIR__));

require_once ROOT . '/config/config.php';
require_once ROOT . '/app/core/Database.php';
require_once ROOT . '/app/core/Controller.php';
require_once ROOT . '/app/core/Router.php';

// Masquer les erreurs en production
$env = getenv('APP_ENV') ?: 'development';
if ($env === 'production') {
    ini_set('display_errors', '0');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

$router = new Router();

$router->get('/',              'CatalogueController', 'index');
$router->get('/catalogue',     'CatalogueController', 'index');
$router->get('/dashboard',     'DashboardController', 'index');

$router->get('/login',         'AuthController', 'loginForm');
$router->post('/login',        'AuthController', 'login');
$router->get('/logout',        'AuthController', 'logout');

$router->get('/livres',            'LivreController', 'index');
$router->get('/livres/create',     'LivreController', 'create');
$router->post('/livres/store',     'LivreController', 'store');
$router->get('/livres/edit/{id}',  'LivreController', 'edit');
$router->post('/livres/update/{id}','LivreController', 'update');
$router->post('/livres/delete/{id}','LivreController', 'delete');

$router->get('/adherents',             'AdherentController', 'index');
$router->get('/adherents/create',      'AdherentController', 'create');
$router->get('/adherents/show/{id}',   'AdherentController', 'recherche');
$router->post('/adherents/store',      'AdherentController', 'store');
$router->get('/adherents/edit/{id}',   'AdherentController', 'edit');
$router->post('/adherents/update/{id}','AdherentController', 'update');
$router->post('/adherents/delete/{id}','AdherentController', 'delete');

$router->get('/emprunts',                    'EmpruntController', 'index');
$router->get('/emprunts/create',             'EmpruntController', 'create');
$router->post('/emprunts/store',             'EmpruntController', 'store');
$router->get('/emprunts/edit/{id}',          'EmpruntController', 'edit');
$router->post('/emprunts/update/{id}',       'EmpruntController', 'update');
$router->post('/emprunts/delete/{id}',       'EmpruntController', 'supprimer');
$router->post('/emprunts/retour/{id}',       'EmpruntController', 'marquerCommeRetourne');

$url = $_GET['url'] ?? '';
$router->dispatch($url);
