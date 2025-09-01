<?php

function view(string $view, array $data = []): void
{
    extract($data);

    $view_path = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';

    if (!file_exists($view_path)) {
        die("Error: la vista '{$view}' no se encontró");
    }

    $layout_file = (isset($layout) && $layout === 'guest') ? 'layout-guest.php' : 'layout.php';

    require_once __DIR__ . '/../views/' . $layout_file;
}

function viewCatalog(string $view, array $data = []): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); 
    }

    extract($data); 


    $nombre_usuario = $_SESSION['user']['usuario'] ?? null;

 
    $view_path = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';

    if (!file_exists($view_path)) {
        die("Error: la vista '{$view}' no se encontró");
    }

    require __DIR__ . '/../views/layout-custom.php';
}


?>