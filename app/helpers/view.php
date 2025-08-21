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

    extract($data); // Extrae $title, etc.

    // Aquí sacamos el nombre del usuario desde la sesión
    $nombre_usuario = $_SESSION['user']['usuario'] ?? null;

    // Armamos la ruta
    $view_path = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';

    if (!file_exists($view_path)) {
        die("Error: la vista '{$view}' no se encontró");
    }

    // Esta variable queda disponible en layout-custom.php
    require __DIR__ . '/../views/layout-custom.php';
}


?>