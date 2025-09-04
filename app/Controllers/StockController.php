<?php
namespace App\Controllers;
use App\Models\CatalogModel;
use App\Models\StockModel;
use App\Models\UserModel;
class StockController
{
    public function showUploadForm()
    {
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

        $fileTmp = $_FILES['archivo_stock']['tmp_name'];

        $stockModel   = new StockModel();
        $userModel    = new UserModel();
        $catalogModel = new CatalogModel();

        $errores      = [];
        $procesados   = 0;
        $insertados   = 0;
        $actualizados = 0;

        if (($handle = fopen($fileTmp, "r")) !== false) {
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $row++;

                // 🚨 Saltar encabezado si la primera fila contiene texto tipo "id_store"
                if ($row === 1) {
                    $primeraCol = strtolower(trim((string)($data[0] ?? '')));
                    if ($primeraCol === 'id_store' || $primeraCol === 'tienda') {
                        continue; // Saltar fila de encabezado
                    }
                }

                // Leer datos
                $idStore     = (int)($data[0] ?? 0);
                $syncId      = trim((string)($data[1] ?? ''));
                $stock       = (int)($data[2] ?? 0);
                $chipperprice = isset($data[3]) ? (int)$data[3] : null;

                // Validar tienda
                $tienda = $userModel->obtenerTiendaPorId($idStore);
                if (!$tienda) {
                    $errores[] = "Fila {$row}: la tienda ID {$idStore} no existe.";
                    continue;
                }

                // Validar producto
                $producto = $catalogModel->existeProductoPorSyncId($syncId);
                if (!$producto) {
                    $errores[] = "Fila {$row}: el producto con sync_id {$syncId} no existe en el catálogo.";
                    continue;
                }

                // Guardar en base de datos
                try {
                    $resultado = $stockModel->guardarStock($idStore, $syncId, $stock, $chipperprice);
                    $procesados++;

                    if ($resultado === 'insertado') {
                        $insertados++;
                    } elseif ($resultado === 'actualizado') {
                        $actualizados++;
                    }
                } catch (\Throwable $e) {
                    $errores[] = "Fila {$row}: error al guardar ({$e->getMessage()}).";
                }
            }
            fclose($handle);
        }

        // Resumen
        $resumen = [
            'procesados'   => $procesados,
            'insertados'   => $insertados,
            'actualizados' => $actualizados
        ];

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
