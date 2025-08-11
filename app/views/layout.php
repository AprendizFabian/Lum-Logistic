<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> | LUM logistic</title>
    <link rel="shortcut icon" href="/images/cropped-favicon-lum-192x192.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php
    require_once __DIR__ . "/Components/headerGuest.php";
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