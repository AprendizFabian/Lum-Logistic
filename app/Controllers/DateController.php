<?php
namespace App\Controllers;
use DateTime;
use DateInterval;
use App\Models\CatalogModel;
use App\Models\HistoryValidador;
use App\Models\MemberModel;
class DateController
{
    public function showFormDate()
    {
        $title = 'Cargue individual, Validador';
        $StoreModel = new MemberModel();
        $tiendas = $StoreModel->getMembers('stores');
        view('Admin/individual_charge', compact('title', 'tiendas'));
    }
    public function MasiveCharge()
    {
        $title = 'Cargue masivo';
        $storeModel = new MemberModel();
        $tiendas = $storeModel->getMembers('stores');
        view('Admin/MasiveCharge', compact('title', 'tiendas'));
    }
public function validar()
{
    header('Content-Type: application/json');

    $ean = trim($_POST['ean'] ?? '');
    $fechaVencimiento = trim($_POST['fecha_vencimiento'] ?? '');

    if ($ean === '' || $fechaVencimiento === '') {
        echo json_encode(['error' => 'Faltan datos obligatorios']);
        return;
    }

    try {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idStore = null;
        if (!empty($_SESSION['auth']['type'])) {
            if ($_SESSION['auth']['type'] === 'store') {
                $idStore = $_SESSION['auth']['id'] ?? null;
            } elseif ($_SESSION['auth']['type'] === 'user') {
                $idStore = $_POST['id_store'] ?? null;
            }
        }

        // cerramos la sesión para no bloquear otras requests
        session_write_close();

        if (empty($idStore)) {
            echo json_encode(['error' => 'No se seleccionó ninguna tienda']);
            return;
        }

        $catalogModel = new CatalogModel();
        $producto = $catalogModel->obtenerProductoPorEan($ean);

        if (!$producto) {
            echo json_encode(['error' => "El EAN {$ean} no existe en el catálogo"]);
            return;
        }

        $sync_id      = $producto['sync_id'] ?? '';
        $descripcion  = $producto['description'] ?? '';
        $categoria    = $producto['shelf_life_concept'] ?? 'Sin categoría';
        $diasVidaUtil = is_numeric($producto['shelf_life_duration'] ?? null)
                        ? (int) $producto['shelf_life_duration'] 
                        : null;

        $fechaBloqueo = '';
        $estado = 'Desconocido';

        $fechaObj = DateTime::createFromFormat('Y-m-d', $fechaVencimiento);

        if ($fechaObj && $diasVidaUtil !== null) {
            $fechaBloqueoObj = clone $fechaObj;
            $fechaBloqueoObj->sub(new DateInterval("P{$diasVidaUtil}D"));
            $fechaBloqueo = $fechaBloqueoObj->format('Y-m-d');

            $hoy = new DateTime('today');

            if ($fechaBloqueoObj == $hoy) {
                $estado = 'BLOQUEAR HOY';
            } elseif ($fechaBloqueoObj > $hoy) {
                $estado = 'NO BLOQUEAR';
            } else {
                $estado = 'VENCIDO';
            }
        }

        $conceptoBloqueo = ($estado === 'VENCIDO') 
            ? 'VENCIDO' 
            : ($producto['shelf_life_concept'] ?? '');

        // Guardar en historial si no existe
        $historialModel = new HistoryValidador();
        $existe = $historialModel->existeEanOFecha($ean, $fechaBloqueo, $idStore);

        if (!$existe) {
            $historialModel->insertarRegistro(
                $ean,
                $sync_id,  
                $descripcion,
                $fechaVencimiento,
                $fechaBloqueo,
                $categoria,
                $diasVidaUtil ?? 0,
                $conceptoBloqueo,
                $estado,
                $idStore
            );
        }

        echo json_encode([
            'ean'               => $ean,
            'sync_id'           => $sync_id,
            'descripcion'       => $descripcion,
            'categoria'         => $categoria,
            'dias_vida_util'    => $diasVidaUtil,
            'fecha_vencimiento' => $fechaVencimiento,
            'fecha_bloqueo'     => $fechaBloqueo,
            'estado'            => $estado,
            'concepto_bloqueo'  => $conceptoBloqueo,
            'observacion'       => $estado,
            'registro_existente'=> $existe,
            'id_store'          => $idStore
        ], JSON_UNESCAPED_UNICODE);

    } catch (\Throwable $e) {
        echo json_encode([
            'error' => 'Error interno: ' . $e->getMessage()
        ]);
    }
}


    public function validarMasivo()
{
    header('Content-Type: application/json; charset=utf-8');
    ini_set('display_errors', '0');
    error_reporting(E_ALL);

    try {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idStore = null;
        if (isset($_SESSION['auth']['type'])) {
            if ($_SESSION['auth']['type'] === 'store') {
                $idStore = $_SESSION['auth']['id'] ?? null;
            } elseif ($_SESSION['auth']['type'] === 'user') {
                $idStore = $_POST['id_store'] ?? null;
            }
        }

        if (empty($idStore)) {
            throw new \Exception('No se seleccionó ninguna tienda.');
        }

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

        // Validar extensión y MIME
        $ext = strtolower(pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            throw new \Exception('El archivo debe ser formato CSV.');
        }
        $mime = mime_content_type($archivoTmp);
        if (!in_array($mime, ['text/plain', 'text/csv', 'application/vnd.ms-excel'])) {
            throw new \Exception("Formato de archivo inválido ($mime).");
        }

        // Normalizar encoding (UTF-8)
        $contenido = file_get_contents($archivoTmp);
        $encoding = mb_detect_encoding($contenido, ['UTF-8', 'ISO-8859-1', 'UTF-16'], true);
        if ($encoding !== 'UTF-8') {
            $contenido = mb_convert_encoding($contenido, 'UTF-8', $encoding);
        }
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $contenido);
        rewind($handle);

        $historialModel = new HistoryValidador();
        $catalogModel   = new CatalogModel();

        $errores = [];
        $linea = 0;
        $registrosParaInsertar = [];
        $vistos = []; // prevenir duplicados dentro del archivo

        while (($fila = fgetcsv($handle, 1000, ',')) !== false) {
            $linea++;

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

            if ($ean === '' || $fechaVencimiento === '') {
                $errores[] = "Línea {$linea}: Faltan datos obligatorios (EAN o fecha de vencimiento).";
                continue;
            }

            $fechaObj = $this->parseFechaFlexible($fechaVencimiento);
            if (!($fechaObj instanceof DateTime)) {
                $errores[] = "Línea {$linea}: La fecha '{$fechaVencimiento}' no tiene un formato válido.";
                continue;
            }

            $producto = $catalogModel->obtenerProductoPorEan($ean);
            if (!$producto) {
                $errores[] = "Línea {$linea}: El EAN {$ean} no existe en el catálogo.";
                continue;
            }

            $sync_id     = $producto['sync_id'] ?? '';
            $descripcion = $producto['description'] ?? '';
            $categoria   = $producto['shelf_life_concept'] ?? 'Sin categoría';
            $diasVidaUtil = $producto['shelf_life_duration'] ?? null;

            if (!is_numeric($diasVidaUtil)) {
                $errores[] = "Línea {$linea}: 'Días de vida útil' para el EAN {$ean} no es numérico.";
                continue;
            }

            $fechaBloqueoObj = clone $fechaObj;
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

            $conceptoBloqueo = $estado === 'VENCIDO' ? 'VENCIDO' : ($producto['shelf_life_concept'] ?? '');

            // Chequear duplicados en el mismo archivo
            $hash = "{$ean}|{$fechaBloqueo}|{$idStore}";
            if (isset($vistos[$hash])) {
                $errores[] = "Línea {$linea}: Duplicado dentro del archivo (EAN {$ean} con fecha de bloqueo {$fechaBloqueo}).";
                continue;
            }
            $vistos[$hash] = true;

            // Chequear duplicados en DB
            if ($historialModel->existeEanOFecha($ean, $fechaBloqueo, $idStore)) {
                $errores[] = "Línea {$linea}: El EAN {$ean} con fecha de bloqueo {$fechaBloqueo} ya existe para esta tienda.";
                continue;
            }

            $registrosParaInsertar[] = [
                $ean,
                $sync_id,
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

        $insertados = 0;
        foreach ($registrosParaInsertar as $registro) {
            $historialModel->insertarRegistro(...$registro);
            $insertados++;
        }

        echo json_encode([
            'mensaje'   => 'Validación finalizada.',
            'insertados'=> $insertados,
            'errores'   => $errores
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

        if (strlen($fechaTexto) < 8) {
            return false;
        }

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