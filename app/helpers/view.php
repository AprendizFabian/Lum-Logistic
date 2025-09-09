<?php

function view(string $view, array $data = []): void
{
    extract($data);

    $view_path = __DIR__ . '/../Views/' . str_replace('.', '/', $view) . '.php';

    if (!file_exists($view_path)) {
        die("Error: la vista '{$view}' no se encontró");
    }

    $layout_file = (isset($layout) && $layout === 'guest') ? 'layout-guest.php' : 'layout-main.php';

    require_once __DIR__ . '/../Views/' . $layout_file;
}
