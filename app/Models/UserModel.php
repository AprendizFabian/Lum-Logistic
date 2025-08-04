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

    // Verificar usuario y contraseña (con comparación directa por ahora)
    public function verifyUser($usuario, $contrasena)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM lum_pruaba.usuarios WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user && $user['contraseña'] === $contrasena) {
            return [
                'id' => $user['id_usuario'] ?? null,
                'usuario' => $user['usuario'],
                'rol_id_rol' => $user['rol_id_rol'],
                'nombre' => $user['nombre'] ?? '',
            ];
        }

        return null;
    }


    public function obtenerUsuarios()
    {
        $sql = "SELECT u.usuario, u.contraseña, r.nombre_rol AS rol 
                FROM lum_pruaba.usuarios u
                JOIN lum_pruaba.rol r ON u.rol_id_rol = r.id_rol";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
