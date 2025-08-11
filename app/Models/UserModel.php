<?php

namespace App\Models;

use App\Database;
use PDO;
use PDOException;

class UserModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function verifyUser($usuario, $contrasena)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM lum_prueba.usuarios WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['contraseña'] === $contrasena) {
            return [
                'idusuarios' => $user['idusuarios'],
                'usuario' => $user['usuario'],
                'rol_id_rol' => $user['rol_id_rol'],
            ];
        }

        return null;
    }

    public function obtenerUsuarios()
    {
        $sql = "SELECT u.idusuarios, u.usuario, r.nombre_rol AS rol 
                FROM lum_prueba.usuarios u
                JOIN lum_prueba.rol r ON u.rol_id_rol = r.id_rol";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarioPorId($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM lum_prueba.usuarios WHERE idusuarios = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Error al obtener usuario: " . $e->getMessage());
            return false;
        }
    }
    public function agregarUsuario($usuario, $contraseña, $rol)
    {
        $stmt = $this->pdo->prepare("INSERT INTO lum_prueba.usuarios (usuario, contraseña, rol_id_rol) VALUES (?, ?, ?)");
        $stmt->execute([$usuario, $contraseña, $rol]);
    }

    public function editarUsuario($id, $usuario, $rol)
    {
        $stmt = $this->pdo->prepare("UPDATE lum_prueba.usuarios SET usuario = ?, rol_id_rol = ? WHERE idusuarios = ?");
        $stmt->execute([$usuario, $rol, $id]);
    }

    public function eliminarUsuario($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM lum_prueba.usuarios WHERE idusuarios = ?");
        $stmt->execute([$id]);
    }
}
