<?php
namespace App\Controllers;

class PageController
{
    public function showView()
    {
        $title = "Inicio";
        $layout = 'guest';

        view('Landing/mainView', compact('title', 'layout'));
    }
}