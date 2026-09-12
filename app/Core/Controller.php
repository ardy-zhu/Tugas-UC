<?php

namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $viewFile = APP_ROOT . '/resources/views/' . ltrim($template, '/') . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException('View file not found: ' . $template);
        }

        require $viewFile;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}
