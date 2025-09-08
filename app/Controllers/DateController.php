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
        $tiendas = $StoreModel->getMembers('store');
        view('Admin/individual_charge', compact('title', 'tiendas'));
    }
    public function MasiveCharge()
    {
        $title = 'Cargue masivo';
        $storeModel = new MemberModel();
        $tiendas = $storeModel->getMembers('store');
        view('Admin/MasiveCharge', compact('title', 'tiendas'));
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
                echo json_encode(['error' => 'No se seleccionó ninguna tienda']);
                return;
            }
            $catalogModel = new CatalogModel();
            $producto = $catalogModel->obtenerProductoPorEan($ean);

            if (!$producto) {
                echo json_encode(['error' => "El EAN {$ean} no existe en el catálogo"]);
                return;
            }
            $descripcion = $producto['description'] ?? '';
            $categoria = $producto['shelf_life_concept'] ?? 'Sin categoría';
            $diasVidaUtil = $producto['shelf_life_duration'] ?? null;


            $conceptoBloqueo = '';
            $fechaBloqueo = '';
            $estado = 'Desconocido';


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

            $conceptoBloqueo = $estado === 'VENCIDO' ? 'VENCIDO' : $producto['shelf_life_concept'] ?? '';


            $historialModel = new HistoryValidador();
            $existe = $historialModel->existeEanOFecha($ean, $fechaBloqueo, $idStore);

            if (!$existe) {
                $historialModel->insertarRegistro(
                    $ean,
                    $descripcion,
                    $fechaVencimiento,
                    $fechaBloqueo,
                    $categoria,
                    $diasVidaUtil,
                    $conceptoBloqueo,
                    $estado,
                    $idStore
                );
            }

            echo json_encode([
                'ean' => $ean,
                'estado' => $estado,
                'descripcion' => $descripcion,
                'dias_vida_util' => $diasVidaUtil,
                'fecha_bloqueo' => $fechaBloqueo,
                'fecha_vencimiento' => $fechaVencimiento,
                'categoria' => $categoria,
                'concepto_bloqueo' => $conceptoBloqueo,
                'observacion' => $estado,
                'registro_existente' => $existe,
                'id_store' => $idStore
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

            $handle = fopen($archivoTmp, 'r');
            if (!$handle) {
                throw new \Exception('No se pudo abrir el archivo para lectura.');
            }

            $historialModel = new HistoryValidador();
            $catalogModel = new CatalogModel();

            $errores = [];
            $linea = 0;
            $registrosParaInsertar = [];

            while (($fila = fgetcsv($handle, 1000, ',')) !== false) {
                $linea++;

                if (empty(array_filter($fila))) {
                    $errores[] = "Línea {$linea}: Fila vacía.";
                    continue;
                }

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

                $descripcion = $producto['description'] ?? '';
                $categoria = $producto['shelf_life_concept'] ?? 'Sin categoría';
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

                if ($historialModel->existeEanOFecha($ean, $fechaBloqueo, $idStore)) {
                    $errores[] = "Línea {$linea}: El EAN {$ean} con fecha de bloqueo {$fechaBloqueo} ya existe para esta tienda.";
                    continue;
                }

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

            if (!empty($errores)) {
                echo json_encode([
                    'mensaje' => 'Validación finalizada con errores. No se insertó ningún registro.',
                    'insertados' => 0,
                    'errores' => $errores
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

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
            $date = DateTime::createFromFormat($formato, $fechaTexto);
            if ($date && $date->format($formato) === $fechaTexto) {
                $year = (int) $date->format('Y');
                if ($year < 1900 || $year > (int) date('Y') + 5) {
                    return false;
                }
                return $date;
            }
        }
        try {
            $date = new DateTime($fechaTexto);
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