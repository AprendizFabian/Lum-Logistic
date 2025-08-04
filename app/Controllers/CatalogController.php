<?php

namespace App\Controllers;

use App\Models\SheetsModel;

// Incluir helper de sesión
require_once __DIR__ . '/../helpers/SessionHelper.php';

class CatalogController
{
    public function showCatalog()
    {
        checkInactivity(350); 
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';

        $sheetModel = new SheetsModel();
        $sheetData = $sheetModel->getData($page, 6, $search);

        $title = 'Catálogo';
        viewCatalog('Admin/catalog', compact('title', 'sheetData'));
    }

    public function showVidaUtil()
    {
        checkInactivity(350);

        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';

        $sheetModel = new SheetsModel();
        $sheetData = $sheetModel->getVidaUtil($page, 6, $search);

        $title = 'Vida Útil';
        viewCatalog('Admin/vida_util', compact('title', 'sheetData'));
    }

    public function ShowDate()
    {
        checkInactivity(350);

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
