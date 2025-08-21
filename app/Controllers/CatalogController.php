<?php

namespace App\Controllers;

use App\Models\SheetsModel;



class CatalogController
{
    public function showCatalog()
    {
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? '';

        $sheetModel = new SheetsModel();
        $sheetData = $sheetModel->getData($page, 8, $search);

        $title = 'Catálogo';
        viewCatalog('Admin/catalog', compact('title', 'sheetData'));
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
