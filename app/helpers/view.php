<?php

function view(string $view, array $data = []): void
{
    extract($data);

    $view_path = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';

    if (!file_exists($view_path)) {
        die("Error: la vista '{$view}' no se encontró");
    }

    // Determina el layout a usar. Por defecto, usa el layout principal.
    // Verificamos si la variable $layout existe y es 'guest'
    $layout_file = (isset($layout) && $layout === 'guest') ? 'layout-guest.php' : 'layout.php';

    require_once __DIR__ . '/../views/' . $layout_file;
}
?>