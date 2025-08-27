<?php
namespace App\Controllers;
use DateTime;
use DateInterval;
use App\Models\SheetsModel;
use App\Models\HistoryValidador;
use App\Models\StoreModel;
class DateController
{
    public function showFormDate()
    {
        $title = 'Validador';
        viewCatalog('Admin/individual_charge', compact('title'));
    }
    public function MasiveCharge() {
        $title = 'Cargue masivo';
        $storeModel = new StoreModel();
        $tiendas = $storeModel->obtenerTiendas();
        viewCatalog('Admin/MasiveCharge', compact('title', 'tiendas'));
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

    try {
        $historialModel = new HistoryValidador();
        $sheetModel = new SheetsModel();
        // Obtener datos desde Google Sheets
        $datos = $sheetModel->obtenerDatosDesdeSheets($ean);
        if (isset($datos['error'])) {
            echo json_encode(['error' => $datos['error']]);
            return;
        }
        if (empty($datos) || empty($datos['descripcion']) || empty($datos['diasVidaUtil'])) {
            echo json_encode(['error' => "El EAN {$ean} no existe en la hoja de cálculo"]);
            return;
        }
        // Procesar datos
        $diasVidaUtil = $datos['diasVidaUtil'];
        $categoria = $datos['categoria'] ?? null;
        $descripcion = $datos['descripcion'];
        $conceptoBloqueo = '';
        $fechaBloqueo = '';
        $estado = 'Desconocido';
        if (!empty($fechaVencimiento)) {
            $fechaBloqueoObj = DateTime::createFromFormat('Y-m-d', $fechaVencimiento);
            if ($fechaBloqueoObj && $diasVidaUtil !== null) {
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
        }

        if ($estado === 'VENCIDO') {
            $conceptoBloqueo = 'VENCIDO';
        } else {
            $concepto = $sheetModel->buscarColumnaPorEan($ean, 3);
            $conceptoBloqueo = $concepto ?? '';
        }
        // Verificar si ya existe registro con ese EAN y fecha de bloqueo
        $existe = $historialModel->existeEanOFecha($ean, $fechaBloqueo);
        // Solo insertar si NO existe
       if (!$existe) {
    $historialModel->insertarRegistro(
        $ean,              // 👈 EAN
        $descripcion,      // 👈 Description
        $fechaVencimiento, // 👈 Expiration Date
        $fechaBloqueo,     // 👈 Block Date
        $categoria,        // 👈 Category
        $diasVidaUtil,     // 👈 Days Lifespan
        $conceptoBloqueo,  // 👈 Block Concept
        $estado,           // 👈 Remarks
        null               // 👈 idStore (null por ahora)
    );
}

        // Responder siempre con los datos procesados
        echo json_encode([
            'ean' => $ean,
            'estado' => $estado,
            'descripcion' => $descripcion,
            'dias_vida_util' => $diasVidaUtil,
            'fecha_bloqueo' => $fechaBloqueo,
            'categoria' => $categoria,
            'concepto_bloqueo' => $conceptoBloqueo,
            'observacion' => $estado,
            'registro_existente' => $existe // true si ya estaba, false si se insertó
        ]);
    } catch (\PDOException $e) {
        echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
    } catch (\Exception $e) {
        echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
    }
}

    public function validarMasivo()
    {
        header('Content-Type: application/json; charset=utf-8');
        ini_set('display_errors', '0');
        error_reporting(E_ALL);

        try {
      $idStore = null;
if (isset($_SESSION['user']['type'])) {
    if ($_SESSION['user']['type'] === 'store') {
        // Si es tienda, usar la sesión
        $idStore = $_SESSION['user']['id_store'] ?? null;
    } elseif ($_SESSION['user']['type'] === 'user') {
        // Si es admin, viene del select
        $idStore = $_POST['id_store'] ?? null;
        if (empty($idStore)) {
            throw new \Exception('No se seleccionó ninguna tienda.');
        }
    }
}


            // 📂 Validación de archivo
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

            $handle = fopen($archivoTmp, 'r');
            if (!$handle) {
                throw new \Exception('No se pudo abrir el archivo para lectura.');
            }

            // 📊 Modelos
            $historialModel = new HistoryValidador();
            $sheetModel     = new SheetsModel();

            $errores = [];
            $linea = 0;
            $registrosParaInsertar = [];

            while (($fila = fgetcsv($handle, 1000, ',')) !== false) {
                $linea++;

                // Saltar filas vacías
                if (empty(array_filter($fila))) {
                    $errores[] = "Línea {$linea}: Fila vacía.";
                    continue;
                }

                // Saltar encabezado
                if ($linea === 1 && isset($fila[0]) && strtolower(trim($fila[0])) === 'ean') {
                    continue;
                }

                $ean              = trim($fila[0] ?? '');
                $fechaVencimiento = trim($fila[1] ?? '');

                // Validaciones de campos
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

                // Validar fecha
                $fechaObj = $this->parseFechaFlexible($fechaVencimiento);
                if (!($fechaObj instanceof \DateTime)) {
                    $errores[] = "Línea {$linea}: La fecha '{$fechaVencimiento}' no tiene un formato válido para el EAN {$ean}.";
                    continue;
                }

                // Buscar datos en Sheets
                $datos = $sheetModel->obtenerDatosDesdeSheets($ean);
                if (!is_array($datos) || isset($datos['error']) || empty($datos['diasVidaUtil'])) {
                    $errores[] = "Línea {$linea}: El EAN {$ean} no existe.";
                    continue;
                }

                $diasVidaUtil = $datos['diasVidaUtil'];
                if (!is_numeric($diasVidaUtil)) {
                    $errores[] = "Línea {$linea}: El valor de 'días de vida útil' para el EAN {$ean} no es numérico ('{$diasVidaUtil}').";
                    continue;
                }

                // Calcular fecha de bloqueo
                try {
                    $diasInt = (int)$diasVidaUtil;
                    $fechaBloqueoObj = clone $fechaObj;
                    $fechaBloqueoObj->sub(new \DateInterval("P{$diasInt}D"));
                    $fechaBloqueo = $fechaBloqueoObj->format('Y-m-d');
                } catch (\Exception $e) {
                    $errores[] = "Línea {$linea}: Error calculando fecha de bloqueo para el EAN {$ean} ({$e->getMessage()}).";
                    continue;
                }

                // Validar duplicados
                if ($historialModel->existeEanOFecha($ean, $fechaBloqueo)) {
                    $errores[] = "Línea {$linea}: El EAN {$ean} con fecha de bloqueo {$fechaBloqueo} ya existe en la base de datos.";
                    continue;
                }

                // Determinar estado
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

                // Datos extra
                $categoria       = $datos['categoria'] ?? '';
                $descripcion     = $datos['descripcion'] ?? '';
                $conceptoBloqueo = $estado === 'VENCIDO'
                    ? 'VENCIDO'
                    : ($sheetModel->buscarColumnaPorEan($ean, 3) ?? '');

                // Guardar registro listo para insertar
                $registrosParaInsertar[] = [
                    $ean,
                    $descripcion,
                    $fechaObj->format('Y-m-d'),
                    $fechaBloqueo,
                    $categoria,
                    $diasVidaUtil,
                    $conceptoBloqueo,
                    $estado,
                    $idStore
                ];
            }
            fclose($handle);

            // 📌 Si hubo errores, no inserta nada
            if (!empty($errores)) {
                echo json_encode([
                    'mensaje'   => 'Validación finalizada con errores. No se insertó ningún registro.',
                    'insertados'=> 0,
                    'errores'   => $errores
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Insertar registros válidos
            $insertados = 0;
            foreach ($registrosParaInsertar as $registro) {
                $historialModel->insertarRegistro(...$registro);
                $insertados++;
            }

            echo json_encode([
                'mensaje'   => 'Validación finalizada sin errores. Registros insertados correctamente.',
                'insertados'=> $insertados,
                'errores'   => []
            ], JSON_UNESCAPED_UNICODE);

        } catch (\Throwable $e) {
            echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }
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