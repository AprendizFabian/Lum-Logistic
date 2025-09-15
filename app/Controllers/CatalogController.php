<?php
namespace App\Controllers;
use App\Middleware\ErrorHandler;
use App\Middleware\AuthMiddleware;
use App\Helpers\Controller;
use App\Models\SheetsModel;
use App\Models\CatalogModel;
use App\Models\StockModel;

class CatalogController
{
    private $catalogModel;
    private $stockModel;
    private $sheetModel;
    private $controllerHelper;

    public function __construct()
    {
        $this->catalogModel = new CatalogModel();
        $this->stockModel = new StockModel();
        $this->sheetModel = new SheetsModel();
        $this->controllerHelper = new Controller();
    }
    public function showCatalog()
    {
        return ErrorHandler::handle(function () {
            AuthMiddleware::requireAuth();

            $page = $_GET['page'] ?? 1;
            $search = $_GET['search'] ?? '';
            $perPage = 9;

            if ($_SESSION['auth']['type'] != 'store') {
                $products = $this->catalogModel->getProducts($search);
            } else {
                $idStore = $_SESSION['auth']['id'] ?? null;
                $products = $this->stockModel->obtenerStockPorTienda($idStore);
            }

            $productsPaginated = $this->controllerHelper->paginate($products, $page, $perPage);

            view('Admin/catalogView', [
                'title' => "Catálogo",
                'layout' => "main",
                'productsPaginated' => $productsPaginated,
            ]);
        });
    }

    public function showVidaUtil()
    {
        return ErrorHandler::handle(function () {
            AuthMiddleware::requireAuth();

            $page = $_GET['page'] ?? 1;
            $search = $_GET['search'] ?? '';
            $perPage = 9;

            $shelfLife = $this->catalogModel->getShelfLife($search);
            $shelfLifePaginated = $this->controllerHelper->paginate($shelfLife, $page, $perPage);

            view('Admin/shelfLifeView', [
                'title' => 'Vida Útil',
                'layout' => "main",
                'shelfLifePaginated' => $shelfLifePaginated
            ]);
        });
    }

    public function ShowDate()
    {
        return ErrorHandler::handle(function () {
            AuthMiddleware::requireAuth();

            $search = $_GET['search'] ?? '';

            $dates = $this->sheetModel->getDatesFromSheets('Fechas!A:B', $search);
            $datesPaginated = $this->controllerHelper->paginate($dates['data']);

            view('Admin/dateView', [
                'title' => "Fechas",
                'layout' => "main",
                'datesPaginated' => $datesPaginated,
            ]);
        });
    }
}