<?php
namespace App\Controllers;
use App\Models\CatalogModel;
use App\Models\StockModel;
use App\Models\UserModel;
class StockController
{
    public function showUploadForm()
    {
            session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 1) {
            header('Location: /login');
            exit;
        }
        $title = "Subir Stock";
        viewCatalog("Admin/ChargeStock", compact("title"));
    }
    public function subirStock()
    {
        $title = "Subir Stock";
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            viewCatalog("Admin/ChargeStock", compact("title"));
            return;
        }

        if (!isset($_FILES['archivo_stock']) || $_FILES['archivo_stock']['error'] !== UPLOAD_ERR_OK) {
            $errores = ["No se recibió un archivo válido."];
            viewCatalog("Admin/ChargeStock", compact("title", "errores"));
            return;
        }

        $tmp = $_FILES['archivo_stock']['tmp_name'];
        if (!is_readable($tmp)) {
            $errores = ["No se puede leer el archivo."];
            viewCatalog("Admin/ChargeStock", compact("title", "errores"));
            return;
        }

        $stockModel = new StockModel();
        $userModel = new UserModel();
        $catalogModel = new CatalogModel();

        $errores = [];
        $resumen = [
            'procesados' => 0,
            'insertados' => 0,
            'actualizados' => 0,
            'skipped' => 0
        ];

        $handle = fopen($tmp, 'r');
        $firstLine = fgets($handle);
        rewind($handle);
        $counts = [
            ',' => substr_count($firstLine, ','),
            ';' => substr_count($firstLine, ';'),
            "\t" => substr_count($firstLine, "\t")
        ];
        arsort($counts);
        $delimiter = array_key_first($counts);

        $row = 0;
        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            $row++;
            $allEmpty = true;
            foreach ($data as $cell) {
                if (trim((string) $cell) !== '') {
                    $allEmpty = false;
                    break;
                }
            }
            if ($allEmpty) {
                $resumen['skipped']++;
                continue;
            }
            if ($row === 1) {
                $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) ($data[0] ?? ''));
            }
            if ($row === 1) {
                $lower0 = strtolower(trim((string) ($data[0] ?? '')));
                if (in_array($lower0, ['id_store', 'tienda', 'store_id', 'store'])) {
                    $resumen['skipped']++;
                    continue;
                }
            }

            if (count($data) < 3) {
                $errores[] = "Fila {$row}: columnas insuficientes (se esperan al menos 3).";
                continue;
            }

            $idStoreRaw = trim((string) ($data[0] ?? ''));
            $syncIdRaw = trim((string) ($data[1] ?? ''));
            $stockRaw = trim((string) ($data[2] ?? ''));
            $priceRaw = trim((string) ($data[3] ?? ''));

            if ($idStoreRaw === '' || !ctype_digit($idStoreRaw)) {
                $errores[] = "Fila {$row}: id_store inválido («{$idStoreRaw}»).";
                continue;
            }
            $idStore = (int) $idStoreRaw;
            if ($idStore <= 0) {
                $errores[] = "Fila {$row}: id_store debe ser positivo.";
                continue;
            }

            if ($syncIdRaw === '') {
                $errores[] = "Fila {$row}: sync_id vacío.";
                continue;
            }
            if ($stockRaw === '' || !is_numeric($stockRaw)) {
                $errores[] = "Fila {$row}: stock inválido («{$stockRaw}»).";
                continue;
            }
            $stockVal = (int) $stockRaw;

            $priceVal = null;
            if ($priceRaw !== '') {
                if (!is_numeric($priceRaw)) {
                    $errores[] = "Fila {$row}: chipperprice inválido («{$priceRaw}»).";
                    continue;
                }
                $priceVal = (int) $priceRaw;
            }

            $tienda = $userModel->obtenerTiendaPorId($idStore);
            if (!$tienda) {
                $errores[] = "Fila {$row}: la tienda ID {$idStore} no existe.";
                continue;
            }

            $producto = $catalogModel->existeProductoPorSyncId($syncIdRaw);
            if (!$producto) {
                $errores[] = "Fila {$row}: sync_id '{$syncIdRaw}' no encontrado en catálogo.";
                continue;
            }

            $res = $stockModel->guardarStockPorSyncId($idStore, $syncIdRaw, $stockVal, $priceVal);
            $resumen['procesados']++;
            if ($res === 'insertado')
                $resumen['insertados']++;
            elseif ($res === 'actualizado')
                $resumen['actualizados']++;
            else
                $errores[] = "Fila {$row}: resultado inesperado al guardar ({$res}).";
        }
        fclose($handle);

        viewCatalog("Admin/ChargeStock", compact("title", "errores", "resumen"));
    }
    public function ver()
    {
        if (!isset($_GET['id_store'])) {
            header("Location: /usuarios");
            exit;
        }

        $idStore = (int) $_GET['id_store'];
        $stockModel = new StockModel();
        $userModel = new UserModel();

        $tienda = $userModel->obtenerTiendaPorId($idStore);
        $productos = $stockModel->obtenerStockPorTienda($idStore);

        $title = "Stock de " . $tienda['store_name'];
        viewCatalog("Stock/verStockView", compact("title", "tienda", "productos"));
    }
}
