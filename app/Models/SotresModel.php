<?php

namespace App\Models;

use App\Database;
use PDO;


class SotresModel
{
      private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }
    public function obtenerTiendas()
{
    $sql = "SELECT s.id_store, s.store_name, s.store_email, r.role_name AS rol
                FROM stores s
                JOIN roles r ON s.id_role = r.id_role";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function agregarTienda($name, $address, $email, $password, $id_role)
{
    $stmt = $this->pdo->prepare("INSERT INTO stores (store_name, store_address, store_email, password, id_role) 
                                 VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $address, $email, $password, $id_role]);
}

}