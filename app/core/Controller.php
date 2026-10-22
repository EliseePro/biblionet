<?php

class Controller
{
    protected function requestedPage(): int
    {
        $page = $_GET['page'] ?? 1;
        return is_scalar($page) ? max(1, (int) $page) : 1;
    }

    protected function paginationData(int $totalItems, int $requestedPage, int $perPage = 10): array
    {
        $perPage = max(1, $perPage);
        $totalPages = max(1, (int) ceil($totalItems / $perPage));
        $page = min(max(1, $requestedPage), $totalPages);

        return [
            'page' => $page,
            'per_page' => $perPage,
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'offset' => ($page - 1) * $perPage,
            'from' => $totalItems === 0 ? 0 : (($page - 1) * $perPage) + 1,
            'to' => min($page * $perPage, $totalItems),
        ];
    }

    protected function csrfToken(): string
    {
        if (!is_string($_SESSION['csrf_token'] ?? null) || $_SESSION['csrf_token'] === '') {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    protected function validateCsrfToken(): bool
    {
        $token = $_POST['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';
        return is_string($token)
            && is_string($sessionToken)
            && $sessionToken !== ''
            && hash_equals($sessionToken, $token);
    }

    protected function requireCsrfToken(): void
    {
        if (!$this->validateCsrfToken()) {
            http_response_code(403);
            exit('Jeton de sécurité invalide. Veuillez recharger la page et réessayer.');
        }
    }

    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        $csrf_token = $data['csrf_token'] ?? $this->csrfToken();
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
