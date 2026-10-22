<?php


class Router
{
    /** @var array<string, array{0: string, 1: string}> */
    private array $routes = [];

    public function add(string $method, string $path, string $controller, string $action): void
    {
        $normalized = rtrim($path, '/') ?: '/';
        $key = strtoupper($method) . ' ' . $normalized;
        $this->routes[$key] = [$controller, $action];
    }

    public function get(string $path, string $controller, string $action): void
    {
        $this->add('GET', $path, $controller, $action);
    }

    public function post(string $path, string $controller, string $action): void
    {
        $this->add('POST', $path, $controller, $action);
    }

    public function dispatch(string $url): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $url = '/' . trim($url, '/');
        if ($url === '//') {
            $url = '/';
        }
        $url = rtrim($url, '/') ?: '/';

        // Correspondance exacte
        $exactKey = $method . ' ' . $url;
        if (isset($this->routes[$exactKey])) {
            $this->call($this->routes[$exactKey][0], $this->routes[$exactKey][1]);
            return;
        }

        // Routes avec {id}
        foreach ($this->routes as $pattern => $handler) {
            [$routeMethod, $routePath] = explode(' ', $pattern, 2);
            if ($routeMethod !== $method || !str_contains($routePath, '{id}')) {
                continue;
            }
            $regex = '#^' . str_replace('{id}', '(\d+)', $routePath) . '$#';
            if (preg_match($regex, $url, $matches)) {
                $this->call($handler[0], $handler[1], (int) $matches[1]);
                return;
            }
        }

        http_response_code(404);
        $title = 'Page introuvable';
        $flash = null;
        $content = '<div class="card"><div class="card-body py-5 text-center">'
            . '<h1 class="h3">404 — Page introuvable</h1>'
            . '<p class="text-muted">URL demandée : <code>'
            . htmlspecialchars($url, ENT_QUOTES, 'UTF-8')
            . '</code></p><a href="' . htmlspecialchars(app_url('/'), ENT_QUOTES, 'UTF-8')
            . '" class="btn btn-primary">Retour à l’accueil</a></div></div>';
        require __DIR__ . '/../views/layouts/main.php';
    }

    private function call(string $controllerName, string $action, ?int $id = null): void
    {
        $file = __DIR__ . '/../controllers/' . $controllerName . '.php';
        if (!is_file($file)) {
            http_response_code(500);
            echo 'Contrôleur introuvable : ' . htmlspecialchars($controllerName);
            return;
        }
        require_once $file;

        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo 'Classe ' . htmlspecialchars($controllerName) . ' introuvable.';
            return;
        }

        $controller = new $controllerName();
        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo 'Méthode ' . htmlspecialchars($controllerName . '::' . $action) . ' introuvable.';
            return;
        }

        if ($id !== null) {
            $controller->$action($id);
        } else {
            $controller->$action();
        }
    }
}
