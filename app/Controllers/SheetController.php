<?php
namespace App\Controllers;
use App\Models\SheetsModel;
class SheetController
{
    public function mostrar()
    {
        $modelo = new SheetsModel();
        $datos = $modelo->getData();

        view('Admin/sheet_data', compact('datos'));
    }
}
