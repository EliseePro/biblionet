<?php

class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!is_file($viewFile)) {
            http_response_code(500);
            echo 'Vue introuvable : ' . htmlspecialchars($view);
            return;
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
        if (is_file($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . app_url($path));
        exit;
    }

    protected function message(string $type, string $text): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $text];
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->message('danger', 'Vous devez être connecté pour accéder à cette page.');
            $this->redirect('/login');
        }
    }

    protected function isLoggedIn(): bool
    {
        return !empty($_SESSION['user_id']);
    }
}
