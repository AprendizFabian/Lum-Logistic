<?php

namespace App\Controllers;
use DateTime;
use DateInterval;
use App\Models\SheetsModel;

class DateController
{
    public function showFormDate()
    {
        $title = 'Validador';
        viewCatalog('Admin/dateJuliana', compact('title'));
    }

public function validar()
{
    // 1️⃣ Recibir datos por POST
    $ean = $_POST['ean'] ?? '';
    $fechaVencimiento = $_POST['fecha_vencimiento'] ?? '';

    if (empty($ean) || empty($fechaVencimiento)) {
        echo json_encode(['error' => 'Faltan datos obligatorios']);
        return;
    }

    // 2️⃣ Obtener datos desde Google Sheets
    $sheetModel = new SheetsModel();
    $datos = $sheetModel->obtenerDatosDesdeSheets($ean);

    if (isset($datos['error'])) {
        echo json_encode(['error' => $datos['error']]);
        return;
    }

    $diasVidaUtil = $datos['diasVidaUtil'] ?? null;
    $categoria = $datos['categoria'] ?? null;
    $descripcion = $datos['descripcion'] ?? null;
    $conceptoBloqueo = '';
    $fechaBloqueo = '';
    $estado = 'Desconocido';

    // 3️⃣ Calcular fecha de bloqueo y estado
    if ($diasVidaUtil !== null && !empty($fechaVencimiento)) {
        $fechaBloqueoObj = DateTime::createFromFormat('Y-m-d', $fechaVencimiento);
        $fechaBloqueoObj->sub(new DateInterval("P{$diasVidaUtil}D"));
        $fechaBloqueo = $fechaBloqueoObj->format('Y-m-d');

        $hoy = new DateTime();
        $hoy->setTime(0, 0); // para evitar problemas con horas
        $fechaBloqueoObj->setTime(0, 0);

        if ($fechaBloqueoObj == $hoy) {
            $estado = 'BLOQUEAR HOY';
        } elseif ($fechaBloqueoObj > $hoy) {
            $estado = 'NO BLOQUEAR';
        } else {
            $estado = 'VENCIDO';
        }
    }

    // 4️⃣ Determinar concepto de bloqueo
    if ($estado === 'VENCIDO') {
        $conceptoBloqueo = 'VENCIDO';
    } else {
        // Buscar columna específica (ahora columna 3)
        $concepto = $sheetModel->buscarColumnaPorEan($ean, 3);
        $conceptoBloqueo = $concepto ?? '';
    }

    // 5️⃣ Guardar en base de datos
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '5432';
    $dbname = getenv('DB_NAME') ?: 'lum';
    $user = getenv('DB_USER') ?: 'postgres';
    $pass = getenv('DB_PASS') ?: '1234';

    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        $pdo = new \PDO($dsn, $user, $pass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);

        $stmt = $pdo->prepare("
            INSERT INTO lum_prueba.historial_validador (
                descripcion,
                dias_vida_util,
                fecha_bloqueo,
                categoria,
                concepto_bloqueo,
                observacion
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $descripcion,
            $diasVidaUtil,
            $fechaBloqueo ?: null,
            $categoria,
            $conceptoBloqueo,
            $estado
        ]);
    } catch (\PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        return;
    }

    // 6️⃣ Respuesta en JSON lista para el modal
    echo json_encode([
        'ean' => $ean,
        'estado' => $estado,
        'descripcion' => $descripcion,
        'dias_vida_util' => $diasVidaUtil,
        'fecha_bloqueo' => $fechaBloqueo,
        'categoria' => $categoria,
        'concepto_bloqueo' => $conceptoBloqueo,
        'observacion' => $estado
    ]);
}

}
