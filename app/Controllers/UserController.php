<?php

namespace App\Controllers;

use App\Models\UserModel;
require_once __DIR__ . '/../helpers/SessionHelper.php';
class UserController
{
    public function showUser()
    {
        session_start();

    
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol_id_rol'] != 2) {
            header('Location: /login');
            exit;
        }

        $modelo = new UserModel();
        $usuarios = $modelo->obtenerUsuarios();

        // Paginación
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 6;
        $total = count($usuarios);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        $usuariosPaginados = array_slice($usuarios, $offset, $perPage);

        $title = 'Usuarios';
        viewCatalog('Admin/userView', compact('title', 'usuariosPaginados', 'page', 'totalPages'));
    }
}
