<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title . ' | LUM Logistic' ?? 'Error: LUM logistic' ?></title>
    <link rel="shortcut icon" href="/images/cropped-favicon-lum-192x192.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/output.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-white text-gray-900">
    <?php
        require __DIR__ . '/components/header.php';
    ?>
    <main>
        <?php require_once $view_path; ?>
    </main>

</body>

</html>