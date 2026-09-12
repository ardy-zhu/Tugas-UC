<?php

namespace App\Core;

use RuntimeException;

final class Router
{
    public function dispatch(): void
    {
        $routeKey = $_GET['page'] ?? null;
        $path = $routeKey ? '/' . trim((string) $routeKey, '/') : $this->currentPath();

        $routes = require APP_ROOT . '/routes/web.php';
        $handler = $routes[$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo '<h2>Halaman tidak ditemukan</h2>';
            return;
        }

        [$controller, $method] = $handler;

        if (!class_exists($controller)) {
            throw new RuntimeException('Controller tidak ditemukan: ' . $controller);
        }

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            throw new RuntimeException('Method tidak ditemukan: ' . $method);
        }

        $instance->$method();
    }

    private function currentPath(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if ($path === '' || $path === '/index.php') {
            return '/';
        }

        return '/' . trim($path, '/');
    }
}
