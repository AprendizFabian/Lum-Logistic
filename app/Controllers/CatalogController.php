<?php
namespace App\Controllers;
use App\Models\SheetsModel;
use App\Models\CatalogModel;
use App\Models\StockModel;
class CatalogController
{
public function showCatalog()
{
    $page   = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 
        ? (int)$_GET['page'] 
        : 1;
    $search = trim($_GET['search'] ?? '');
    $perPage = 8;

    $catalogModel = new CatalogModel();
    $stockModel   = new StockModel();

    $type    = $_SESSION['auth']['type'] ?? 'user'; 
    $idStore = ($type === 'store') ? ($_SESSION['auth']['id'] ?? null) : null;
if (!empty($idStore)) {
    $productosFull = $stockModel->obtenerStockPorTienda($idStore);
    if (!empty($search)) {
        $productosFull = array_filter($productosFull, function($p) use ($search) {
            return stripos($p['product_name'] ?? '', $search) !== false
                || stripos($p['ean'] ?? '', $search) !== false;
        });
    }
    $total = count($productosFull);
    $totalPages = max(1, ceil($total / $perPage));
    if ($page > $totalPages) {
        $page = $totalPages;
    }

    $offset = ($page - 1) * $perPage;
    $productos = array_slice($productosFull, $offset, $perPage);
} else {
    $total = $catalogModel->contarProductos($search);
    $totalPages = max(1, ceil($total / $perPage));
    if ($page > $totalPages) {
        $page = $totalPages;
    }
    $offset = ($page - 1) * $perPage;
    $productos = $catalogModel->obtenerProductos($search, $perPage, $offset);
}
    $title = 'Catálogo';
    view('Admin/catalog', compact(
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
        view('Admin/vida_util', compact('title', 'sheetData'));
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
        view('Admin/fecha', compact('title', 'fechasData', 'layout'));
    }
}