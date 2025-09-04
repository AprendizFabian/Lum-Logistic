<?php

use App\Controllers\PageController;
use App\Controllers\AuthController;
use App\Controllers\DateController;
use App\Controllers\CatalogController;
use App\Controllers\UserController;
use App\Controllers\StockController;


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
$router->get('/usuario', [UserController::class, 'verDetalle']);
$router->post('/eliminar', [UserController::class, 'eliminarUsuario']);
$router->post('/agregar', [UserController::class, 'agregarUsuario']);
$router->post('/editar', [UserController::class, 'editarUsuario']);
$router->post('/eliminarT', [UserController::class, 'eliminarTienda']);
$router->post('/agregarT', [UserController::class, 'agregarTienda']);
$router->post('/editarT', [UserController::class, 'editarTienda']);
$router->post('/validar', [DateController::class, 'validar']);
$router->post('/validar-masivo', [DateController::class,'validarMasivo']);
$router->get('/Masivo', [DateController::class, 'MasiveCharge']);
$router->post('/Activar', [UserController::class, 'activarUsuario']);
$router->post('/ActivarStore', [UserController::class, 'cambiarEstadoTienda']);
$router->get('/stock', [StockController::class, 'showUploadForm']);
$router->post('/stock/subir', [StockController::class, 'subirStock']);