<?php 

use App\Controllers\PageController;
use App\Controllers\AuthController;
use App\Controllers\FechaControler;
use App\Controllers\CatalogoControler;

$router->get("/", [PageController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/procesar-login', [AuthController::class, 'procesarLogin']);
$router->get('/fecha-juliana', [FechaControler::class, 'formulario']);
$router->post('/convertir-fecha', [FechaControler::class, 'convertir']);
$router->get('/catalogo', [CatalogoControler::class, 'mostrarCatalogo']);
