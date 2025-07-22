<?php
namespace App\Controllers;

class PageController
{
    public function index()
    {
        $title = "Inicio";

        view('Landing/index', compact('title'));
    }
}