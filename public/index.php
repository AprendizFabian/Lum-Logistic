<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Router;

require_once __DIR__ . '/../app/helpers/view.php';

$router = new Router();

require_once __DIR__ . '/../routes/web.php';

$router->dispatch();