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

    public function guardarStockPorSyncId($idStore, $syncId, $stock, $chipperprice = null)
    {
        $stmt = $this->pdo->prepare("SELECT id_product, sync_id FROM catalog WHERE sync_id = :sync_id LIMIT 1");
        $stmt->execute([':sync_id' => $syncId]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$producto) {
            return 'no_product';
        }
        $idProduct = (int) $producto['id_product'];

        $check = $this->pdo->prepare("SELECT COUNT(*) FROM store_stock WHERE id_store = :id_store AND id_product = :id_product");
        $check->execute([':id_store' => $idStore, ':id_product' => $idProduct]);
        $exists = (bool) $check->fetchColumn();

        $sql = "
            INSERT INTO store_stock (id_store, id_product, total_stock, chipperprice, sync_id)
            VALUES (:id_store, :id_product, :total_stock, :chipperprice, :sync_id_val)
            ON DUPLICATE KEY UPDATE
                total_stock = :total_stock_upd,
                chipperprice = :chipperprice_upd,
                sync_id = :sync_id_upd
        ";
        $stmt2 = $this->pdo->prepare($sql);
        $params = [
            ':id_store' => $idStore,
            ':id_product' => $idProduct,
            ':total_stock' => $stock,
            ':chipperprice' => $chipperprice,
            ':sync_id_val' => $syncId,
            ':total_stock_upd' => $stock,
            ':chipperprice_upd' => $chipperprice,
            ':sync_id_upd' => $syncId
        ];
        $stmt2->execute($params);

        return $exists ? 'actualizado' : 'insertado';
    }

   public function obtenerStockPorTienda($idStore)
{
    $sql = "
        SELECT ss.id_stock, ss.total_stock, ss.chipperprice, ss.sync_id,
               c.id_product, c.ean, c.description, c.image_url,
               s.concept AS shelf_life_concept,
               s.duration AS shelf_life_duration
        FROM store_stock ss
        JOIN catalog c ON ss.id_product = c.id_product
        LEFT JOIN shelf_life s ON c.id_shelf_life = s.id_shelf_life
        WHERE ss.id_store = :id_store
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id_store' => $idStore]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function contarStockPorTienda($idStore, $search = '')
    {
        $sql = "SELECT COUNT(*) 
            FROM store_stock ss
            JOIN catalog c ON ss.id_product = c.id_product
            WHERE ss.id_store = :id_store";
        if (!empty($search)) {
            $sql .= " AND (c.ean LIKE :s OR c.description LIKE :s)";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_store', $idStore, PDO::PARAM_INT);
        if (!empty($search))
            $stmt->bindValue(':s', "%$search%", PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function obtenerStockPorTiendaPaginado($idStore, $search = '', $limit = 10, $offset = 0)
    {
        $sql = "SELECT ss.total_stock, ss.chipperprice, ss.sync_id,
                   c.id_product, c.ean, c.description, c.image_url
            FROM store_stock ss
            JOIN catalog c ON ss.id_product = c.id_product
            WHERE ss.id_store = :id_store";
        if (!empty($search)) {
            $sql .= " AND (c.ean LIKE :s OR c.description LIKE :s)";
        }
        $sql .= " ORDER BY c.id_product ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_store', $idStore, PDO::PARAM_INT);
        if (!empty($search))
            $stmt->bindValue(':s', "%$search%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
