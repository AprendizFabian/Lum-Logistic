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
        ini_set('display_errors', '0');
        error_reporting(E_ALL);

        try {
            // --- Verificar archivo ---
            if (!isset($_FILES['archivo'])) {
                throw new \Exception('No se recibió ningún archivo para validar.');
            }
            if ($_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception('Error al subir el archivo. Código: ' . $_FILES['archivo']['error']);
            }

            $archivoTmp = $_FILES['archivo']['tmp_name'];
            if (!is_readable($archivoTmp)) {
                throw new \Exception('El archivo no se pudo leer o está dañado.');
            }

            if (!($handle = fopen($archivoTmp, 'r'))) {
                throw new \Exception('No se pudo abrir el archivo para lectura.');
            }

            $historialModel = new HistoryValidador();
            $sheetModel = new SheetsModel();

            $errores = [];
            $linea = 0;
            $registrosParaInsertar = [];

            while (($fila = fgetcsv($handle, 1000, ',')) !== false) {
                $linea++;

                // Saltar fila vacía
                if (empty(array_filter($fila))) {
                    $errores[] = "Línea {$linea}: Fila vacía.";
                    continue;
                }

                // Saltar cabecera
                if ($linea === 1 && isset($fila[0]) && strtolower(trim($fila[0])) === 'ean') {
                    continue;
                }

                $ean = trim($fila[0] ?? '');
                $fechaVencimiento = trim($fila[1] ?? '');

                // --- Validar campos obligatorios ---
                if ($ean === '' && $fechaVencimiento === '') {
                    $errores[] = "Línea {$linea}: No se encontró ni el EAN ni la fecha de vencimiento.";
                    continue;
                }
                if ($ean === '') {
                    $errores[] = "Línea {$linea}: Falta el EAN.";
                    continue;
                }
                if ($fechaVencimiento === '') {
                    $errores[] = "Línea {$linea}: Falta la fecha de vencimiento para el EAN {$ean}.";
                    continue;
                }

                // --- Validar formato de fecha ---
                $fechaObj = $this->parseFechaFlexible($fechaVencimiento);
                if (!($fechaObj instanceof \DateTime)) {
                    $errores[] = "Línea {$linea}: La fecha '{$fechaVencimiento}' no tiene un formato válido para el EAN {$ean}.";
                    continue;
                }

                // --- Obtener datos externos (Sheets) ---
                $datos = $sheetModel->obtenerDatosDesdeSheets($ean);
                if (!is_array($datos) || isset($datos['error']) || !array_key_exists('diasVidaUtil', $datos) || $datos['diasVidaUtil'] === null || $datos['diasVidaUtil'] === '') {
                    $errores[] = "Línea {$linea}: El EAN {$ean} no existe.";
                    continue;
                }

                $diasVidaUtil = $datos['diasVidaUtil'];
                if (!is_numeric($diasVidaUtil)) {
                    $errores[] = "Línea {$linea}: El valor de 'días de vida útil' para el EAN {$ean} no es numérico ('{$diasVidaUtil}').";
                    continue;
                }

                // --- Calcular fecha de bloqueo ---
                try {
                    $diasInt = (int) $diasVidaUtil;
                    $fechaBloqueoObj = clone $fechaObj;
                    $fechaBloqueoObj->sub(new \DateInterval("P{$diasInt}D"));
                    $fechaBloqueo = $fechaBloqueoObj->format('Y-m-d');
                } catch (\Exception $e) {
                    $errores[] = "Línea {$linea}: Error calculando fecha de bloqueo para el EAN {$ean} ({$e->getMessage()}).";
                    continue;
                }

                // --- Validar duplicado ---
                if ($historialModel->existeEanOFecha($ean, $fechaBloqueo)) {
                    $errores[] = "Línea {$linea}: El EAN {$ean} con fecha de bloqueo {$fechaBloqueo} ya existe en la base de datos.";
                    continue;
                }

                // --- Calcular estado ---
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

                $categoria = $datos['categoria'] ?? '';
                $descripcion = $datos['descripcion'] ?? '';
                $conceptoBloqueo = $estado === 'VENCIDO'
                    ? 'VENCIDO'
                    : ($sheetModel->buscarColumnaPorEan($ean, 3) ?? '');

                // Guardar en lista para insertar después
                $registrosParaInsertar[] = [
                    $descripcion,
                    $diasInt,
                    $fechaBloqueo,
                    $categoria,
                    $conceptoBloqueo,
                    $estado,
                    $ean
                ];
            } // fin while

            fclose($handle);

            // --- Decidir si insertar ---
            if (!empty($errores)) {
                echo json_encode([
                    'mensaje' => 'Validación finalizada con errores. No se insertó ningún registro.',
                    'insertados' => 0,
                    'errores' => $errores
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Insertar todos si no hay errores
            $insertados = 0;
            foreach ($registrosParaInsertar as $registro) {
                $historialModel->insertarRegistro(...$registro);
                $insertados++;
            }

            echo json_encode([
                'mensaje' => 'Validación finalizada sin errores. Registros insertados correctamente.',
                'insertados' => $insertados,
                'errores' => []
            ], JSON_UNESCAPED_UNICODE);

        } catch (\Throwable $e) {
            echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }


    /**
     * Convierte cualquier formato de fecha a DateTime (o false si no es válido)
     */
    private function parseFechaFlexible($fechaTexto)
    {
        $fechaTexto = trim((string) $fechaTexto);
        if ($fechaTexto === '') {
            return false;
        }

        // Rechazar si es muy corta
        if (strlen($fechaTexto) < 8) {
            return false;
        }

        // Reemplazar varios separadores por "/"
        $fechaTexto = str_replace(['.', '-', '_', '  ', ' '], '/', $fechaTexto);
        $fechaTexto = preg_replace('#/{2,}#', '/', $fechaTexto);

        $formatos = [
            'd/m/Y',
            'j/n/Y',
            'd/m/y',
            'j/n/y',
            'Y/m/d',
            'y/m/d',
            'm/d/Y',
            'm/d/y'
        ];

        foreach ($formatos as $formato) {
            $date = \DateTime::createFromFormat($formato, $fechaTexto);
            if ($date && $date->format($formato) === $fechaTexto) {
                $year = (int) $date->format('Y');
                if ($year < 1900 || $year > (int) date('Y') + 5) {
                    return false;
                }
                return $date;
            }
        }

        try {
            $date = new \DateTime($fechaTexto);
            $year = (int) $date->format('Y');
            if ($year < 1900 || $year > (int) date('Y') + 5) {
                return false;
            }
            return $date;
        } catch (\Exception $e) {
            return false;
        }
    }

}