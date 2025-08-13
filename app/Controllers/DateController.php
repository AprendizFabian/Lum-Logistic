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

            $insertados = 0;
            $errores = [];
            $linea = 0;

            while (($fila = fgetcsv($handle, 1000, ',')) !== false) {
                $linea++;

                // Saltar si fila vacía
                if (empty(array_filter($fila))) {
                    $errores[] = "Línea {$linea}: Fila vacía.";
                    continue;
                }

                // Saltar cabecera si aplica
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

                // --- Validar duplicados (en tu tabla historial) ---
                if ($historialModel->existeEanOFecha($ean, $fechaVencimiento)) {
                    $errores[] = "Línea {$linea}: El EAN {$ean} con fecha {$fechaVencimiento} ya existe en la base de datos.";
                    continue;
                }

                // --- Obtener datos externos (Sheets) ---
                $datos = $sheetModel->obtenerDatosDesdeSheets($ean);

                // Si no hay datos o hay error, consideramos que el EAN no existe
                if (!is_array($datos) || isset($datos['error']) || !array_key_exists('diasVidaUtil', $datos) || $datos['diasVidaUtil'] === null || $datos['diasVidaUtil'] === '') {
                    $errores[] = "Línea {$linea}: El EAN {$ean} no existe.";
                    continue;
                }

                // --- Calcular fecha de bloqueo y estado ---
                $diasVidaUtil = $datos['diasVidaUtil'];
                $categoria = $datos['categoria'] ?? '';
                $descripcion = $datos['descripcion'] ?? '';
                $estado = 'Desconocido';
                $fechaBloqueo = '';

                // Verificar que días de vida útil sea numérico
                if (!is_numeric($diasVidaUtil)) {
                    $errores[] = "Línea {$linea}: El valor de 'días de vida útil' para el EAN {$ean} no es numérico ('{$diasVidaUtil}').";
                    continue;
                }

                // Hacer el cálculo de bloqueo
                try {
                    $diasInt = (int) $diasVidaUtil;
                    $fechaBloqueoObj = clone $fechaObj;
                    $fechaBloqueoObj->sub(new \DateInterval("P{$diasInt}D"));
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
                } catch (\Exception $e) {
                    $errores[] = "Línea {$linea}: Error calculando fecha de bloqueo para el EAN {$ean} ({$e->getMessage()}).";
                    continue;
                }

                $conceptoBloqueo = $estado === 'VENCIDO'
                    ? 'VENCIDO'
                    : ($sheetModel->buscarColumnaPorEan($ean, 3) ?? '');

                // --- Insertar registro con captura de errores específicos ---
                try {
                    $historialModel->insertarRegistro(
                        $descripcion,
                        $diasInt,
                        $fechaBloqueo,
                        $categoria,
                        $conceptoBloqueo,
                        $estado,
                        $ean
                    );
                    $insertados++;
                } catch (\PDOException $e) {
                    $mensajeError = strtolower($e->getMessage());
                    if (str_contains($mensajeError, 'null value') || str_contains($mensajeError, 'not null')) {
                        $errores[] = "Línea {$linea}: No se pudo guardar el registro porque falta un dato obligatorio para el EAN {$ean}.";
                    } elseif (str_contains($mensajeError, 'duplicate') || str_contains($mensajeError, 'unique')) {
                        $errores[] = "Línea {$linea}: Este registro ya está en la base de datos (EAN {$ean}).";
                    } elseif (str_contains($mensajeError, 'foreign key')) {
                        $errores[] = "Línea {$linea}: El valor ingresado para el EAN {$ean} no coincide con las referencias esperadas (clave foránea).";
                    } else {
                        // Mensaje completo para debugging, pero manténlo entendible
                        $errores[] = "Línea {$linea}: Error al guardar en base de datos para el EAN {$ean} ({$e->getMessage()}).";
                    }
                }
            } // fin while

            fclose($handle);

            echo json_encode([
                'mensaje'    => 'Validación finalizada',
                'insertados' => $insertados,
                'errores'    => $errores
            ], JSON_UNESCAPED_UNICODE);

        } catch (\Throwable $e) {
            // Respuesta amigable en caso de fallo crítico
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
        'd/m/Y', 'j/n/Y',
        'd/m/y', 'j/n/y',
        'Y/m/d', 'y/m/d',
        'm/d/Y', 'm/d/y'
    ];

    foreach ($formatos as $formato) {
        $date = \DateTime::createFromFormat($formato, $fechaTexto);
        if ($date && $date->format($formato) === $fechaTexto) {
            $year = (int) $date->format('Y');
            if ($year < 1900 || $year > (int)date('Y') + 5) {
                return false;
            }
            return $date;
        }
    }

    try {
        $date = new \DateTime($fechaTexto);
        $year = (int) $date->format('Y');
        if ($year < 1900 || $year > (int)date('Y') + 5) {
            return false;
        }
        return $date;
    } catch (\Exception $e) {
        return false;
    }
}

}