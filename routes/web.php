<?php 

use App\Controllers\PageController;
use App\Controllers\AuthController;
use App\Controllers\DateController;
use App\Controllers\CatalogController;
use App\Controllers\UserController;

$router->get("/", [PageController::class, 'showView']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/procesar-login', [AuthController::class, 'processLogin']);
$router->get('/fecha-juliana', [DateController::class, 'showFormDate']);
$router->post('/convertir-fecha', [DateController::class, 'convert']);
$router->get('/catalogo', [CatalogController::class, 'showCatalog']);
$router->get('/vida-util', [CatalogController::class, 'showVidaUtil']);
$router->get('/fecha', [CatalogController::class, 'ShowDate']);
$router->get('/usuarios', [UserController::class, 'showUser']);
$router->get('/logout', [AuthController::class, 'logout']);





