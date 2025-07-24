<?php

namespace App\Controllers;

class FechaControler
{
    public function formulario()
    {
        require_once __DIR__ . '/../views/fechajuliana.php';
    }

    public function convertir()
    {
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

        require_once __DIR__ . '/../views/fechajuliana.php';
    }
}
