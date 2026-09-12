<?php

namespace App\Core;

use RuntimeException;

final class Router
{
    public function dispatch(): void
    {
        $routeKey = $_GET['page'] ?? $this->legacyRoute($_GET['act'] ?? null);
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

    private function legacyRoute(?string $action): ?string
    {
        if ($action === null || $action === '') {
            return null;
        }

        return match ($action) {
            'data' => 'data',
            'model' => 'model',
            'pb' => 'pembelian',
            'gd' => 'gudang',
            'pro' => 'produksi',
            'pro_pemotongan' => 'pemotongan',
            'pro_retur' => 'retur-produksi',
            'pro_penerimaan' => 'penerimaan-hasil-kerja',
            'pro_spk' => 'surat-perintah-kerja',
            'pro_proses_pemotongan' => 'proses-pemotongan',
            'pb2' => 'pembelian-bahan-baku',
            'pen' => 'penjualan',
            'lap' => 'laporan',
            'lap1', 'lap2', 'lap3', 'lap4' => 'laporan-detail',
            'lain' => 'lain-lain',
            default => $action,
        };
    }
}
