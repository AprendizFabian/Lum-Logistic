<?php
namespace App\Models;

use App\Models\SheetsModel;
use DateTime;

class ProductValidatorModel
{
    public function validarProducto($ean, $fechaVencimiento)
    {
        $sheets = new SheetsModel();
        $vidaUtil = $sheets->obtenerDatosDesdeSheets($ean);

        if ($vidaUtil === null) {
            return ['error' => 'EAN no encontrado en hoja V.U'];
        }

        $fechaVenc = new DateTime($fechaVencimiento);
        $hoy = new DateTime();
        $fechaBloqueo = clone $fechaVenc;
        $fechaBloqueo->modify("-$vidaUtil days");

        $diff = $fechaVenc->diff($hoy)->days;
        $signo = $fechaVenc < $hoy ? -1 : 1;
        $diasRestantes = $signo * $diff;

        if ($diasRestantes > $vidaUtil) {
            $estado = "VIGENTE";
            $observacion = "";
        } elseif ($diasRestantes == $vidaUtil) {
            $estado = "PRÓXIMO A VENCER";
            $observacion = "BLOQUEAR HOY";
        } else {
            $estado = "VENCIDO";
            $observacion = "NO APTO";
        }

        return [
            'fechaBloqueo' => $fechaBloqueo->format('Y-m-d'),
            'diasVidaUtil' => $vidaUtil,
            'estado' => $estado,
            'observacion' => $observacion
        ];
    }
}
