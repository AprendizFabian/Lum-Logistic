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

    $sqlUser = "SELECT u.*, r.id_role, 'user' AS type 
                FROM users u
                JOIN roles r ON u.id_role = r.id_role
                WHERE email = :email LIMIT 1";
    $stmt = $this->pdo->prepare($sqlUser);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) { 
            return [
                'id' => $user['id_user'],
                'username' => $user['username'],
                'email' => $user['email'],
                'id_role' => $user['id_role'],
                'type' => 'user'
            ];
        }
        return null; 
    }


    $sqlStore = "SELECT s.*, r.id_role, 'store' AS type
                 FROM stores s
                 JOIN roles r ON s.id_role = r.id_role
                 WHERE store_email = :email LIMIT 1";
    $stmt = $this->pdo->prepare($sqlStore);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $store = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($store) {
      if (password_verify($password, $user['password'])) { 
            return [
                'id' => $store['id_store'],
                'username' => $store['store_name'],
                'email' => $store['store_email'],
                'id_role' => $store['id_role'],
                'type' => 'store'
            ];
        }
    }

    return null;
}    
public function obtenerUsuarios()
    {
        $sql = "SELECT u.id_user, u.username, u.email, r.role_name AS rol
                FROM users u
                JOIN roles r ON u.id_role = r.id_role";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

 public function obtenerUsuarioPorId($id, $type = 'user')
{
    if ($type === 'user') {
        $stmt = $this->pdo->prepare("SELECT u.*, r.role_name AS rol
                                     FROM users u
                                     JOIN roles r ON u.id_role = r.id_role
                                     WHERE u.id_user = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif ($type === 'store') {
        $stmt = $this->pdo->prepare("SELECT s.*, r.role_name AS rol
                                     FROM stores s
                                     JOIN roles r ON s.id_role = r.id_role
                                     WHERE s.id_store = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

//TIENDAS//
public function obtenerTiendas()
{
    $sql = "SELECT s.id_store, s.store_name, s.store_email, r.role_name AS rol
                FROM stores s
                JOIN roles r ON s.id_role = r.id_role";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
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

    public function eliminarUsuario($id_user)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id_user = ?");
        $stmt->execute([$id_user]);
    }

    public function agregarTienda($name, $address, $email, $password, $id_role)
{
    $stmt = $this->pdo->prepare("INSERT INTO stores (store_name, store_address, store_email, password, id_role) 
                                 VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $address, $email, $password, $id_role]);
}

public function editarTienda($id_store, $name, $address, $email,  $id_role)
{
    $stmt = $this->pdo->prepare("UPDATE stores 
                                 SET store_name = ?, store_address = ?, store_email = ?, id_role = ?
                                 WHERE id_store = ?");
    $stmt->execute([$name, $address, $email, $id_role, $id_store]);
}

public function eliminarTienda($id_store)
{
    $stmt = $this->pdo->prepare("DELETE FROM stores WHERE id_store = ?");
    $stmt->execute([$id_store]);
}

}