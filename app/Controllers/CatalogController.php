<?php
namespace App\Controllers;

use App\Models\SheetsModel;

class CatalogController
{
    public function showView()
    {
        $page = $_GET['page'] ?? 1;
        $sheetModel = new SheetsModel();
        $sheetData = $sheetModel->getData($page);

        $title = 'Catalogo';
        view('Admin/catalog', compact('title', 'sheetData'));
    }
}
