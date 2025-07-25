<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> | LUM logistic</title>
    <link rel="shortcut icon" href="/images/cropped-favicon-lum-192x192.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/output.css">
</head>
<body>
    <?php
        require_once __DIR__ . "/Components/header.php";
    ?>
    <div class="min-h-full">
        <main>
            <div>
                <?php require_once $view_path; ?>
            </div>
        </main>
    </div>
</body>
</html>