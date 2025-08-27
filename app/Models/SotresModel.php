<?php
use App\Database;
class StoreModel {
    private $pdo;
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }
    // Obtener todas las tiendas
    public function obtenerTiendas() {
        $sql = "SELECT s.id_store, s.store_name, s.store_address, s.store_email, s.is_active, 
                       r.role_name AS rol
                FROM stores s
                LEFT JOIN roles r ON s.id_role = r.id_role";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
