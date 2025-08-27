<?php
namespace App\Models;

use PDO;
use App\Database; 
class StoreModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function obtenerTiendas() {
        $sql = "SELECT id_store, store_name 
                FROM stores 
                WHERE is_active = 1"; 
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
