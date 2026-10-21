<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function loginForm(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $this->render('auth/login', ['errors' => [], 'old' => []]);
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $email    = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $errors = [];
        if ($email === '') {
            $errors['email'] = 'L\'email est obligatoire.';
        }
        if ($password === '') {
            $errors['password'] = 'Le mot de passe est obligatoire.';
        }

        if ($errors !== []) {
            $this->render('auth/login', ['errors' => $errors, 'old' => ['email' => $email]]);
            return;
        }

        $user = User::authenticate($email, $password);
        if ($user === null) {
            $this->render('auth/login', [
                'errors' => ['email' => 'Identifiants incorrects.'],
                'old'    => ['email' => $email],
            ]);
            return;
        }

        $_SESSION['user_id']    = (int) $user['id'];
        $_SESSION['user_nom']   = $user['nom'];
        $_SESSION['user_email'] = $user['email'];

        $this->message('success', 'Bienvenue, ' . $user['nom'] . ' !');
        $this->redirect('/livres');
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        session_start();
        $this->message('success', 'Vous êtes déconnecté.');
        $this->redirect('/login');
    }
}
