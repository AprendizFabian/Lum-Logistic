<?php
namespace App\Controllers;

class PageController
{
    public function showView()
    {
        $title = "Inicio";
        $layout = 'main';

        view('Landing/mainView', compact('title', 'layout'));
    }
}