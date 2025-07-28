<?php

namespace App\Models;

use App\Database;
use PDO;

class UserModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }
    public function verifyUser($usuario, $contrasena)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM lum_prueba.usuarios WHERE usuario = :usuario AND contraseña = :contrasena");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
