<?php

namespace Microfw\Src\Routing\Router;

class Router {

    private array $routes = [];

    public function get(string $route, callable $action) {
        $this->routes['GET'][$route] = $action;
    }

    public function post(string $route, callable $action) {
        $this->routes['POST'][$route] = $action;
    }

    public function any(string $route, callable $action) {
        $this->get($route, $action);
        $this->post($route, $action);
    }

    public function resolve(Request $request) {
        // pega somente o path
        $uri = parse_url($request->uri(), PHP_URL_PATH);
        $uri = trim($uri, '/');

        // 🔒 ignora arquivos físicos (css, js, img, fonts…)
        if (pathinfo($uri, PATHINFO_EXTENSION)) {
            return;
        }

        // 🔒 ignora pastas internas do sistema
        $ignoredPaths = [
            'vendor',
            'storage',
            'uploads',
            'assets',
            'node_modules',
            'public',
        ];

        foreach ($ignoredPaths as $ignored) {
            if ($uri === $ignored || str_starts_with($uri, $ignored . '/')) {
                return;
            }
        }

        // normaliza novamente com /
        $uri = '/' . $uri;

        $method = $request->method();

        // 🔹 rotas registradas
        foreach ($this->routes[$method] ?? [] as $route => $action) {
            if ($this->match($route, $uri, $params)) {
                return call_user_func_array($action, $params);
            }
        }

        // 🔹 rotas automáticas
        if ($this->resolveAuto($uri, $method)) {
            return;
        }

        // ❌ 404
        http_response_code(404);
        require $_SERVER['DOCUMENT_ROOT'] . '/src/Main/View/notFound.php';
    }

    private function match(string $route, string $uri, &$params): bool {
        $pattern = preg_replace('#\{[\w]+\}#', '([^/]+)', $route);
        $pattern = "#^{$pattern}$#";

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches);
            $params = $matches;
            return true;
        }

        return false;
    }

    public function exportRoutes(): array {
        $export = [];

        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $uri => $action) {

                // ❌ ignora closures
                if ($action instanceof \Closure) {
                    continue;
                }

                // ✅ só strings Controller@method
                if (is_string($action)) {
                    $export[$method][$uri] = $action;
                }
            }
        }

        return $export;
    }

    public function importRoutes(array $routes): void {
        $this->routes = $routes;
    }

    private function resolveAuto(string $uri, string $method): bool {
        // remove query string
        $uri = parse_url($uri, PHP_URL_PATH);

        // normaliza
        $segments = array_values(array_filter(explode('/', trim($uri, '/'))));
        if (!$segments)
            return false;

        $context = strtolower(array_shift($segments));

        // monta path REAL (sem ucfirst)
        $path = implode('/', $segments ?: ['Home']);
        $pathController = implode('/', array_map('ucfirst', $segments ?: ['Home']));

        if ($context === 'app') {
            $controllerBase = $_SERVER['DOCUMENT_ROOT'] . '/src/Main/Controller/Public/';
            $viewBase = $_SERVER['DOCUMENT_ROOT'] . '/src/Main/View/Public/';
        } elseif ($context === 'panel') {
            $controllerBase = $_SERVER['DOCUMENT_ROOT'] . '/src/Main/Controller/Admin/';
            $viewBase = $_SERVER['DOCUMENT_ROOT'] . '/src/Main/View/Admin/';
        } elseif ($context === 'api') {
            $controllerBase = $_SERVER['DOCUMENT_ROOT'] . '/src/Main/Controller/Api/';
            $viewBase = $_SERVER['DOCUMENT_ROOT'] . '/src/Main/View/Api/';
        }elseif ($context === 'landing') {
            $controllerBase = $_SERVER['DOCUMENT_ROOT'] . '/src/Main/Controller/Landing/';
            $viewBase = $_SERVER['DOCUMENT_ROOT'] . '/src/Main/View/Landing/';
        } else {
            return false;
        }

        /** 1️⃣ Controller */
        $controller = $controllerBase . $pathController . '.php';
        if (file_exists($controller)) {
            $this->runMiddlewares(dirname($controller), $method);

            // Para POST, disponibilize $_POST ou php://input
            if ($method === 'POST') {
                // lê JSON enviado via fetch, se houver
                $input = file_get_contents('php://input');
                if ($input) {
                    $_POST = json_decode($input, true) ?? $_POST;
                }
            }

            require $controller;
            return true;
        }

        /** 2️⃣ View */
        $view = $viewBase . $path . '.php';
        if (file_exists($view)) {
            require $view;
            return true;
        }

        return false;
    }

    private function runMiddlewares(string $dir, string $method): void {
        $root = realpath($_SERVER['DOCUMENT_ROOT'] . '/src');

        $dir = realpath($dir);

        while ($dir && strpos($dir, $root) === 0) {

            $middleware = $dir . '/.middleware.php';
            if (file_exists($middleware)) {
                require_once $middleware;
            }

            // sobe um nível
            $parent = dirname($dir);

            // evita loop infinito
            if ($parent === $dir) {
                break;
            }

            $dir = $parent;
        }
    }
}
