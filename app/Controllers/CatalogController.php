<?php
namespace App\Controllers;
use App\Models\SheetsModel;
use App\Models\CatalogModel;
use App\Models\StockModel;
class CatalogController
{
public function showCatalog()
{
    session_start();

    $page   = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 
        ? (int)$_GET['page'] 
        : 1;
    $search = trim($_GET['search'] ?? '');
    $perPage = 8;

    $catalogModel = new CatalogModel();
    $stockModel   = new StockModel();
    $idStore = $_SESSION['id_store'] ?? null;

    if (!empty($idStore)) {
        $productos = $stockModel->obtenerStockPorTienda($idStore);
        $total = count($productos);
        $totalPages = 1;
        $page = 1;
    } else {
        // 🌍 Admin o usuario normal → catálogo nacional
        $total = $catalogModel->contarProductos($search);
        $totalPages = max(1, ceil($total / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $perPage;
        $productos = $catalogModel->obtenerProductos($search, $perPage, $offset);
    }

    $title = 'Catálogo';
    viewCatalog('Admin/catalog', compact(
        'title',
        'productos',
        'page',
        'totalPages',
        'search',
        'total'
    ));
}

    public function showVidaUtil()
    {
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';

        $sheetModel = new SheetsModel();
        $sheetData = $sheetModel->getVidaUtil($page, 9, $search);

        $title = 'Vida Útil';
        viewCatalog('Admin/vida_util', compact('title', 'sheetData'));
    }

    public function ShowDate()
    {
        $title = 'Fechas';
        $modelo = new SheetsModel();
        $buscar = $_GET['buscar'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 6;
        $fechasData = $modelo->getFechasDesdeOtroArchivo('Fechas!A:B', $page, $perPage, $buscar);
        $layout = '';
        viewCatalog('Admin/fecha', compact('title', 'fechasData', 'layout'));
    }
}