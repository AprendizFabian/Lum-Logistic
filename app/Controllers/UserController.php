<?php

namespace App\Controllers;

use App\Models\UserModel;
require_once __DIR__ . '/../helpers/SessionHelper.php';

class UserController
{
    public function showUser()
    {
        session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['id_role'] != 1) {
            header('Location: /login');
            exit;
        }

        $modelo = new UserModel();
        $usuarios = $modelo->obtenerUsuarios(); // SELECT * FROM users
        
        $tiendas = $modelo->obtenerTiendas();
        // --- Paginación ---
        $page = $_GET['page'] ?? 1;
        $perPage = 6;
        $total = count($usuarios);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        $page = $_GET['page'] ?? 1;
        $perPage = 6;
        $total = count($tiendas);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        $tiendasPaginadas = array_slice($tiendas, $offset, $perPage);

        $usuariosPaginados = array_slice($usuarios, $offset, $perPage);

        // --- Edición / Eliminación ---
        $usuarioAEditar = null;
        $usuarioAEliminar = null;

        if (isset($_GET['editar'])) {
            $usuarioAEditar = $modelo->obtenerUsuarioPorId((int) $_GET['editar']);
        }

        if (isset($_GET['eliminar'])) {
            $usuarioAEliminar = $modelo->obtenerUsuarioPorId((int) $_GET['eliminar']);
        }

        $title = 'Usuarios';
        viewCatalog('Admin/userView', compact(
            'title',
            'usuariosPaginados',
            'page',
            'totalPages',
            'usuarioAEditar',
            'usuarioAEliminar',
            'tiendasPaginadas'
        ));
    }

 


    public function verDetalle()
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $id = $_SESSION['username']['id_user']; // PK real

        $modelo = new UserModel();
        $usuario = $modelo->obtenerUsuarioPorId($id);

        if (!$usuario) {
            echo "Usuario no encontrado.";
            exit;
        }

        $title = 'Detalle de Usuario';
        viewCatalog('Admin/ShowUser', compact('title', 'usuario'));
    }

    public function agregarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash seguro
            $rol = $_POST['id_role'];

            $modelo = new UserModel();
            $modelo->agregarUsuario($username, $email, $password, $rol);

            header('Location: /usuarios');
            exit;
        }
    }

    public function editarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_user'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $rol = $_POST['id_role'];

            $modelo = new UserModel();
            $modelo->editarUsuario($id, $username, $email, $rol);

            header('Location: /usuarios');
            exit;
        }
    }

    public function eliminarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_user'];

            $modelo = new UserModel();
            $modelo->eliminarUsuario($id);

            header('Location: /usuarios');
            exit;
        }
    }



    public function agregarTienda()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['store_name'];
            $email = $_POST['store_email'];
            $address = $_POST['store_address'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash seguro
            $id_role = $_POST['id_role'];



            $modelo = new UserModel();
            $modelo->agregarTienda($name, $address, $email, $password, $id_role);

            header('Location: /usuarios');
            exit;
        }
    }

    public function editarTienda()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_store'];
            $name = $_POST['name'];
            $address = $_POST['address'];
            $email = $_POST['email'];
            $id_role = $_POST['id_role'];

            $modelo = new UserModel();
            $modelo->editarTienda($id, $name, $address, $email, $id_role);

            header('Location: /tiendas');
            exit;
        }
    }

    public function eliminarTienda()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_store'];

            $modelo = new UserModel();
            $modelo->eliminarTienda($id);

            header('Location: /tiendas');
            exit;
        }
    }
}





