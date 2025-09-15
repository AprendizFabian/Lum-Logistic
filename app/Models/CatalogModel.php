<?php
namespace App\Models;
use App\Database;
use PDO;

class CatalogModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function getProducts($search = '')
    {
        $sql = "SELECT 
                    c.id_product, 
                    c.ean, 
                    c.description, 
                    c.sync_id,
                    c.vivo_id,
                    c.block_status,
                    c.image_url,
                    c.id_shelf_life, 
                    s.concept AS shelf_life_concept,
                    s.duration AS shelf_life_duration
                FROM catalog c
                LEFT JOIN shelf_life s ON c.id_shelf_life = s.id_shelf_life";

        if (!empty($search)) {
            $sql .= " WHERE c.description LIKE :search1 OR c.ean LIKE :search2 ORDER BY c.id_product ASC";
            $search = '%' . $search . '%';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':search1', $search, PDO::PARAM_STR);
            $stmt->bindValue(':search2', $search, PDO::PARAM_STR);
        } else {
            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getShelfLife($search = '')
    {
        $sql = "SELECT * FROM shelf_life";

        if (!empty($search)) {
            $sql .= " WHERE concept LIKE :search";
            $search = '%' . $search . '%';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':search', $search, PDO::PARAM_STR);
        } else {
            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getProductByEan($ean)
    {
        $sql = "SELECT c.id_product, c.ean, c.description, 
                   s.concept AS shelf_life_concept, 
                   s.duration AS shelf_life_duration
                FROM catalog c
                LEFT JOIN shelf_life s ON c.id_shelf_life = s.id_shelf_life
                WHERE c.ean = :ean
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':ean', $ean, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function existsProductBySyncId($syncId)
    {
        $sql = "SELECT id_product FROM catalog WHERE sync_id = :sync_id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':sync_id' => $syncId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

}
