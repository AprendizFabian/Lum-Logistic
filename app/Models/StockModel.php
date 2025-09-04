<?php
namespace App\Models;
use App\Database;
use PDO;
class StockModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }


public function guardarStock($idStore, $syncId, $stock, $chipperprice = null)
{
    $sql = "
        INSERT INTO store_stock (id_store, id_product, total_stock, chipperprice, sync_id)
        SELECT :id_store, c.id_product, :total_stock, :chipperprice, c.sync_id
        FROM catalog c
        WHERE c.sync_id = :sync_id
        ON DUPLICATE KEY UPDATE
            total_stock  = VALUES(total_stock),
            chipperprice = VALUES(chipperprice)
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':id_store'     => $idStore,
        ':total_stock'  => $stock,
        ':chipperprice' => $chipperprice,
        ':sync_id'      => $syncId
    ]);

    return $stmt->rowCount() > 0 ? 'insertado/actualizado' : 'sin cambios';
}


    public function obtenerStockPorTienda($idStore)
    {
        $sql = "
            SELECT ss.id_stock, ss.total_stock, ss.chipperprice, ss.sync_id,
                   c.id_product, c.ean, c.description, c.image_url
            FROM store_stock ss
            JOIN catalog c ON ss.id_product = c.id_product
            WHERE ss.id_store = :id_store
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id_store' => $idStore]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
