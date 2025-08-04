<?php

namespace App\Controllers;
require_once __DIR__ . '/../helpers/SessionHelper.php';class DateController
{
    public function showFormDate()
    {
        $title = 'Validador';
        viewCatalog('Admin/dateJuliana', compact('title'));
    }

    public function convert()
    {
         checkInactivity(350);
        $title = 'Validador';
        $input = $_POST['fecha_juliana'] ?? '';

        if (!preg_match('/^\d{5}$/', $input)) {
            $resultado = "Formato inválido";
        } else {
            $anio = intval("20" . substr($input, 0, 2));
            $diaJuliano = intval(substr($input, 2));
            $fecha = new \DateTime();
            $fecha->setDate($anio, 1, 1);
            $fecha->modify('+' . ($diaJuliano - 1) . ' days');
            $resultado = $fecha->format('d/m/Y');
        }

        $title = "Inicio";
        $layout = 'guest';
        view('Landing/mainView', compact('title', 'layout'));
    }
}
