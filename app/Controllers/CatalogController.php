<?php
namespace App\Controllers;

class CatalogController
{
    public function showCatalog()
    {
        $title = 'Catalogo';
        view('Admin/catalogo', compact('title'));
    }
}
