<?php 

use App\Controllers\PageController;
use App\Controllers\AuthController;

$router->get("/", [PageController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/procesar-login', [AuthController::class, 'procesarLogin']);
