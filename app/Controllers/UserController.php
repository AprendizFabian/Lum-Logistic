<?php

namespace App\Controllers;

use App\Models\UserModel;
require_once __DIR__ . '/../helpers/SessionHelper.php';

class UserController
{
    public function showUser()
    {
        session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['rol_id_rol'] != 1) {
            header('Location: /login');
            exit;
        }

        $modelo = new UserModel();
        $usuarios = $modelo->obtenerUsuarios();

        $page = $_GET['page'] ?? 1;
        $perPage = 6;
        $total = count($usuarios);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        $usuariosPaginados = array_slice($usuarios, $offset, $perPage);

        $usuarioAEditar = null;
        $usuarioAEliminar = null;

        if (isset($_GET['editar'])) {
            $usuarioAEditar = $modelo->obtenerUsuarioPorId($_GET['editar']);
        }

        if (isset($_GET['eliminar'])) {
            $usuarioAEliminar = $modelo->obtenerUsuarioPorId($_GET['eliminar']);
        }

        $title = 'Usuarios';
        viewCatalog('Admin/userView', compact('title', 'usuariosPaginados', 'page', 'totalPages', 'usuarioAEditar', 'usuarioAEliminar'));

    }

    public function verDetalle()
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $id = $_SESSION['user']['idusuarios'];

        $modelo = new UserModel();
        $usuario = $modelo->obtenerUsuarioPorId($id);

        if (empty($usuario)) {
            echo "Usuario no encontrado.";
            exit;
        }

        $title = 'Detalle de Usuario';
        viewCatalog('Admin/ShowUser', compact('title', 'usuario'));
    }
    public function agregarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'];
            $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); // Seguridad
            $rol = $_POST['rol'];

            $modelo = new UserModel();
            $modelo->agregarUsuario($usuario, $contraseña, $rol);

            header('Location: /usuarios');
            exit;
        }
    }

    public function editarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $usuario = $_POST['usuario'];
            $rol = $_POST['rol'];

            $modelo = new UserModel();
            $modelo->editarUsuario($id, $usuario, $rol);

            header('Location: /usuarios');
            exit;
        }
    }

    public function eliminarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $modelo = new UserModel();
            $modelo->eliminarUsuario($id);

            header('Location: /usuarios');
            exit;
        }
    }

}
