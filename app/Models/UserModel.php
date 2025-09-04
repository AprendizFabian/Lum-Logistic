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

    public function verifyUser($email, $password)
    {
     
        $sqlUser = "SELECT * FROM users WHERE email = :email AND is_active = 1";
        $stmt = $this->pdo->prepare($sqlUser);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return [
                'id' => $user['id_user'],
                'username' => $user['username'],
                'email' => $user['email'],
                'id_role' => $user['id_role'],
                'type' => 'user'
            ];
        }

        $sqlStore = "SELECT * FROM stores WHERE store_email = :email AND is_active = 1";
        $stmt = $this->pdo->prepare($sqlStore);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $store = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($store && password_verify($password, $store['password'])) {
            return [
                'id' => $store['id_store'],
                'username' => $store['store_name'],
                'email' => $store['store_email'],
                'id_role' => $store['id_role'],
                'type' => 'store'
            ];
        }

        return false;
    }
    public function obtenerUsuarios()
    {
        $sql = "SELECT u.id_user, u.username, u.is_active, u.email, r.role_name AS rol
                FROM users u
                JOIN roles r ON u.id_role = r.id_role";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUsuarioPorId($id)
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            u.id_user,
            u.username,
            u.email,
            u.is_active,
            u.id_role,       
            r.role_name AS rol
        FROM users u
        JOIN roles r ON u.id_role = r.id_role
        WHERE u.id_user = ?
    ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
public function obtenerTiendaPorId($id)
{
    $stmt = $this->pdo->prepare("
        SELECT s.id_store, s.store_name, s.store_address, s.store_email, s.created_at, s.is_active, s.id_role,
               c.city_name, r.role_name AS rol
        FROM stores s
        LEFT JOIN cities c ON s.city_id = c.id_city
        LEFT JOIN roles r ON s.id_role = r.id_role
        WHERE s.id_store = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function obtenerTiendas()
{
    $stmt = $this->pdo->query("
        SELECT s.id_store, s.store_name, s.store_address, s.store_email, s.created_at, s.is_active,
               c.city_name, r.role_name AS rol
        FROM stores s
        LEFT JOIN cities c ON s.city_id = c.id_city
        LEFT JOIN roles r ON s.id_role = r.id_role
        ORDER BY s.id_store DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function obtenerCiudades()
{
    $stmt = $this->pdo->query("SELECT id_city, city_name FROM cities ORDER BY city_name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



    public function agregarUsuario($username, $email, $password, $id_role)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password, id_role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password, $id_role]);
    }

    public function editarUsuario($id_user, $username, $email, $id_role)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET username = ?, email = ?, id_role = ? WHERE id_user = ?");
        $stmt->execute(params: [$username, $email, $id_role, $id_user]);
    }

    public function cambiarEstadoUsuario($id_user, $estado)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET is_active = ? WHERE id_user = ?");
        $stmt->execute([$estado, $id_user]);
    }


public function agregarTienda($name, $address, $email, $password, $id_role, $city_id = null)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO stores (store_name, store_address, store_email, password, id_role, city_id) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $address, $email, $password, $id_role, $city_id]);
    }


    public function editarTienda($id_store, $name, $address, $email, $id_role)
    {
        $check = $this->pdo->prepare("SELECT COUNT(*) FROM stores WHERE store_email = ? AND id_store <> ?");
        $check->execute([$email, $id_store]);
        if ($check->fetchColumn() > 0) {
            throw new \Exception("El correo $email ya está en uso por otra tienda.");
        }

        $stmt = $this->pdo->prepare("
        UPDATE stores 
        SET store_name = ?, store_address = ?, store_email = ?, id_role = ?
        WHERE id_store = ?
    ");
        $stmt->execute([$name, $address, $email, $id_role, $id_store]);
    }
    public function cambiarEstadoTienda($id, $nuevoEstado)
    {
        $sql = "UPDATE stores SET is_active = :estado WHERE id_store = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':estado', $nuevoEstado, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateLastLogin($userId)
    {
        $sql = "UPDATE users SET last_login = NOW() WHERE id_user = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
    }
}