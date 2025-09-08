<?php

use App\Controllers\PageController;
use App\Controllers\AuthController;
use App\Controllers\DateController;
use App\Controllers\CatalogController;

use App\Controllers\MemberController;
use App\Controllers\StockController;

$router->get("/", [PageController::class, 'showView']);

$router->group('/users', function ($router) {
    $router->get("/", [MemberController::class, 'showMembers']);
    $router->get("/user", [MemberController::class, 'showDetails']);
    $router->post("/addMember", [MemberController::class, 'addMember']);
    $router->post("/editMember", [MemberController::class, 'editMember']);
    $router->post("/changeStatus", [MemberController::class, 'changeStatus']);
});

$router->group('/auth', function ($router) {
    $router->get("/login", [AuthController::class, 'showLogin']);
    $router->get("/logout", [AuthController::class, 'logout']);
    $router->post("/processLogin", [AuthController::class, 'processLogin']);
});

$router->group('/Date', function ($router) {
    $router->get('/DateJuliana', [DateController::class, 'showFormDate']);

});
$router->post('/convertir-fecha', [DateController::class, 'convert']);
$router->post('/validar-masivo', [DateController::class, 'validarMasivo']);
$router->post('/validar', [DateController::class, 'validar']);
$router->get('/Masivo', [DateController::class, 'MasiveCharge']);
$router->get('/catalogo', [CatalogController::class, 'showCatalog']);
$router->get('/vida-util', [CatalogController::class, 'showVidaUtil']);
$router->get('/fecha', [CatalogController::class, 'ShowDate']);

$router->get('/stock', [StockController::class, 'showUploadForm']);
$router->post('/stock/subir', [StockController::class, 'subirStock']);
