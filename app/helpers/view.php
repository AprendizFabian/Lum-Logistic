<?php

function view(string $view, array $data = []): void
{
    extract($data);

    $view_path = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';

    if (!file_exists($view_path)) {
        die("Error: la vista '{$view}' no se encontro");
    }

    require_once __DIR__ . '/../views/layout.php';
}