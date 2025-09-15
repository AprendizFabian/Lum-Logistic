<?php
namespace App\models;

use App\Database;
use PDO;

class CatalogModel 
{
    private $pdo;
    protected $table = 'catalog';

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function obtenerProductos($search = '', $limit = 10, $offset = 0)
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
                FROM {$this->table} c
                LEFT JOIN shelf_life s ON c.id_shelf_life = s.id_shelf_life";
        
        if (!empty($search)) {
            $sql .= " WHERE c.ean LIKE :search1 OR c.description LIKE :search2";
        }

        $sql .= " ORDER BY c.id_product ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search1', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search2', "%$search%", PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarProductos($search = '')
    {
        $sql = "SELECT COUNT(*) as total 
                FROM {$this->table} c
                LEFT JOIN shelf_life s ON c.id_shelf_life = s.id_shelf_life";
        
        if (!empty($search)) {
            $sql .= " WHERE c.ean LIKE :search1 OR c.description LIKE :search2";
        }

        $stmt = $this->pdo->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search1', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search2', "%$search%", PDO::PARAM_STR);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }
public function obtenerProductoPorEan($ean)
{
    $sql = "SELECT 
                c.id_product, 
                c.ean, 
                c.description, 
                c.sync_id,  
                s.concept AS shelf_life_concept, 
                s.duration AS shelf_life_duration
            FROM {$this->table} c
            LEFT JOIN shelf_life s ON c.id_shelf_life = s.id_shelf_life
            WHERE c.ean = :ean
            LIMIT 1";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':ean', $ean, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function existeProductoPorSyncId($syncId)
{
    $sql = "SELECT id_product FROM catalog WHERE sync_id = :sync_id LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':sync_id' => $syncId]);
    return $stmt->fetch(PDO::FETCH_ASSOC); 
}

}
