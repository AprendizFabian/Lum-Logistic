<?php

namespace App\Controllers;

use DateTime;
use DateInterval;

use App\Models\SheetsModel;

use App\Models\HistoryValidador;

class DateController
{
    public function showFormDate()
    {
        $title = 'Validador';
        viewCatalog('Admin/dateJuliana', compact('title'));
    }

    public function validar()
    {
        header('Content-Type: application/json');

        $ean = $_POST['ean'] ?? '';
        $fechaVencimiento = $_POST['fecha_vencimiento'] ?? '';

        if (empty($ean) || empty($fechaVencimiento)) {
            echo json_encode(['error' => 'Faltan datos obligatorios']);
            return;
        }

        // Variables de entorno para BD
        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '5432';
        $dbname = getenv('DB_NAME') ?: 'lum';
        $user = getenv('DB_USER') ?: 'postgres';
        $pass = getenv('DB_PASS') ?: '1234';

        try {
            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
            $pdo = new \PDO($dsn, $user, $pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);

            // Verificar si ya existe
            $checkStmt = $pdo->prepare("
                SELECT *
                FROM lum_prueba.historial_validador
                WHERE ean = ? OR fecha_bloqueo = ?
                LIMIT 1
            ");
            $checkStmt->execute([$ean, $fechaVencimiento]);
            $existing = $checkStmt->fetch(\PDO::FETCH_ASSOC);

            if ($existing) {
                echo json_encode([
                    'ean' => $existing['ean'],
                    'estado' => $existing['observacion'],
                    'descripcion' => $existing['descripcion'],
                    'dias_vida_util' => $existing['dias_vida_util'],
                    'fecha_bloqueo' => $existing['fecha_bloqueo'],
                    'categoria' => $existing['categoria'],
                    'concepto_bloqueo' => $existing['concepto_bloqueo'],
                    'observacion' => $existing['observacion'],
                    'mensaje' => 'Registro ya existente'
                ]);
                return;
            }
        } catch (\PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        // Datos desde Google Sheets
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

        if ($diasVidaUtil !== null && !empty($fechaVencimiento)) {
            $fechaBloqueoObj = DateTime::createFromFormat('Y-m-d', $fechaVencimiento);
            $fechaBloqueoObj->sub(new DateInterval("P{$diasVidaUtil}D"));
            $fechaBloqueo = $fechaBloqueoObj->format('Y-m-d');

            $hoy = new DateTime();
            $hoy->setTime(0, 0);
            $fechaBloqueoObj->setTime(0, 0);

            if ($fechaBloqueoObj == $hoy) {
                $estado = 'BLOQUEAR HOY';
            } elseif ($fechaBloqueoObj > $hoy) {
                $estado = 'NO BLOQUEAR';
            } else {
                $estado = 'VENCIDO';
            }
        }

        if ($estado === 'VENCIDO') {
            $conceptoBloqueo = 'VENCIDO';
        } else {
            $concepto = $sheetModel->buscarColumnaPorEan($ean, 3);
            $conceptoBloqueo = $concepto ?? '';
        }

        // Insertar en la base de datos
        try {
            $stmt = $pdo->prepare("
                INSERT INTO lum_prueba.historial_validador (
                    descripcion,
                    dias_vida_util,
                    fecha_bloqueo,
                    categoria,
                    concepto_bloqueo,
                    observacion,
                    ean
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $descripcion,
                $diasVidaUtil,
                $fechaBloqueo ?: null,
                $categoria,
                $conceptoBloqueo,
                $estado,
                $ean
            ]);
        } catch (\PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

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

public function validarMasivo()
{
    header('Content-Type: application/json; charset=utf-8');
    ini_set('display_errors', 0);
    error_reporting(E_ALL);

    try {
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('No se envió el archivo o hubo un error al subirlo');
        }

        $archivoTmp = $_FILES['archivo']['tmp_name'];
        if (!($handle = fopen($archivoTmp, 'r'))) {
            throw new \Exception('No se pudo abrir el archivo');
        }

        $historialModel = new HistoryValidador();
        $sheetModel = new SheetsModel();

        $insertados = 0;
        $errores = [];
        $linea = 0;

        while (($fila = fgetcsv($handle, 1000, ',')) !== false) {
            $linea++;

            if ($linea == 1 && strtolower($fila[0]) === 'ean') {
                continue;
            }

            $ean = trim($fila[0] ?? '');
            $fechaVencimiento = trim($fila[1] ?? '');

            if (empty($ean) || empty($fechaVencimiento)) {
                $errores[] = "Línea {$linea}: Faltan datos obligatorios";
                continue;
            }

            if ($historialModel->existeEanOFecha($ean, $fechaVencimiento)) {
                $errores[] = "Línea {$linea}: EAN {$ean} ya existente en BD";
                continue;
            }

            $datos = $sheetModel->obtenerDatosDesdeSheets($ean);
            if (isset($datos['error'])) {
                $errores[] = "Línea {$linea}: {$datos['error']}";
                continue;
            }

            $diasVidaUtil = $datos['diasVidaUtil'] ?? null;
            $categoria = $datos['categoria'] ?? null;
            $descripcion = $datos['descripcion'] ?? null;
            $conceptoBloqueo = '';
            $fechaBloqueo = '';
            $estado = 'Desconocido';

            if ($diasVidaUtil !== null && !empty($fechaVencimiento)) {
                $fechaBloqueoObj = $this->parseFechaFlexible($fechaVencimiento);
                if ($fechaBloqueoObj instanceof \DateTime) {
                    $fechaBloqueoObj->sub(new \DateInterval("P{$diasVidaUtil}D"));
                    $fechaBloqueo = $fechaBloqueoObj->format('Y-m-d');

                    $hoy = new \DateTime();
                    $hoy->setTime(0, 0);
                    $fechaBloqueoObj->setTime(0, 0);

                    if ($fechaBloqueoObj == $hoy) {
                        $estado = 'BLOQUEAR HOY';
                    } elseif ($fechaBloqueoObj > $hoy) {
                        $estado = 'NO BLOQUEAR';
                    } else {
                        $estado = 'VENCIDO';
                    }
                } else {
                    $errores[] = "Línea {$linea}: Formato de fecha inválido ({$fechaVencimiento})";
                    continue;
                }
            }

            $conceptoBloqueo = $estado === 'VENCIDO' ? 'VENCIDO' : ($sheetModel->buscarColumnaPorEan($ean, 3) ?? '');

            try {
                $historialModel->insertarRegistro($descripcion, $diasVidaUtil, $fechaBloqueo, $categoria, $conceptoBloqueo, $estado, $ean);
                $insertados++;
            } catch (\PDOException $e) {
                $errores[] = "Línea {$linea}: Error BD - {$e->getMessage()}";
            }
        }

        fclose($handle);

        echo json_encode([
            'mensaje' => 'Proceso finalizado',
            'insertados' => $insertados,
            'errores' => $errores
        ], JSON_UNESCAPED_UNICODE);

    } catch (\Throwable $e) {
        echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    }
}

/**
 * Convierte cualquier formato de fecha a DateTime
 */
private function parseFechaFlexible($fechaTexto)
{
    $fechaTexto = trim($fechaTexto);
    if ($fechaTexto === '') {
        return false;
    }

    // Reemplazar separadores comunes por "/"
    $fechaTexto = str_replace(['.', '-', '_', ' '], '/', $fechaTexto);

    // Formatos comunes
    $formatos = [
        'd/m/Y', 'j/n/Y',
        'd/m/y', 'j/n/y',
        'Y/m/d', 'y/m/d',
        'm/d/Y', 'm/d/y'
    ];

    foreach ($formatos as $formato) {
        $date = \DateTime::createFromFormat($formato, $fechaTexto);
        if ($date && $date->format($formato) === $fechaTexto) {
            return $date;
        }
    }

    // Último intento: que PHP lo interprete
    try {
        return new \DateTime($fechaTexto);
    } catch (\Exception $e) {
        return false;
    }
}


}