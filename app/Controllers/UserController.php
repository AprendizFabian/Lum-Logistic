<?php
namespace App\Controllers;
use App\Models\UserModel;
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
        $usuarios = $modelo->obtenerUsuarios();

        $tiendas = $modelo->obtenerTiendas();
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

        $tiendaEditar = null;
        if (isset($_GET['editar_store'])) {
            $tiendaEditar = $modelo->obtenerTiendaPorId((int) $_GET['editar_store']);
        }

        $usuarioAEditar = null;
        if (isset($_GET['editar'])) {
            $usuarioAEditar = $modelo->obtenerUsuarioPorId((int) $_GET['editar']);
        }
        $usuarioEstado = null;
        if (isset($_GET['confirmar_toggle']) && is_numeric($_GET['confirmar_toggle'])) {
            $usuarioEstado = $modelo->obtenerUsuarioPorId($_GET['confirmar_toggle']);
        }

        $title = 'Usuarios';
        viewCatalog('Admin/userView', compact(
            'title',
            'usuariosPaginados',
            'page',
            'totalPages',
            'usuarioAEditar',
            'tiendaEditar',
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

    $tipo = $_SESSION['user']['type'];
    $id   = $_SESSION['user']['id'];

    $modelo = new UserModel();

    if ($tipo === 'user') {
        $usuario = $modelo->obtenerUsuarioPorId($id);
    } else {
        $usuario = $modelo->obtenerTiendaPorId($id);
    }

    if (!$usuario) {
        echo "Usuario/Tienda no encontrado.";
        exit;
    }

    $title = $tipo === 'user' ? 'Detalle de Usuario' : 'Detalle de Tienda';
    viewCatalog('Admin/ShowDetails', compact('title', 'usuario'));
}

    public function agregarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
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

    public function activarUsuario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_user'] ?? null;
            $modelo = new UserModel();
            $usuario = $modelo->obtenerUsuarioPorId($id);
            if (!$usuario) {
                header('Location: /usuarios?error=notfound');
                exit;
            }
            $nuevoEstado = $usuario['is_active'] == 1 ? 0 : 1;
            $modelo->cambiarEstadoUsuario($id, $nuevoEstado);
            header('Location: /usuarios?success=estado');
            exit;
        }
    }
   public function agregarTienda()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name     = trim($_POST['store_name'] ?? '');
        $email    = trim($_POST['store_email'] ?? '');
        $address  = trim($_POST['store_address'] ?? '');
        $password = $_POST['password'] ?? '';
        $id_role  = $_POST['id_role'] ?? 3; 
        $city_id  = $_POST['city_id'] ?? '';

        if (empty($name) || empty($email) || empty($address) || empty($password) || empty($city_id)) {
            header('Location: /usuarios?error=missing_fields');
            exit;
        }

        // Hash de la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Guardar en el modelo
        $modelo = new UserModel(); // o UserModel si está en el mismo
        $modelo->agregarTienda($name, $address, $email, $passwordHash, $id_role, $city_id);

        header('Location: /usuarios?success=store_added');
        exit;
    }

    // Si no es POST
    header('Location: /usuarios');
    exit;
}

    public function editarTienda()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_store'];
            $name = $_POST['store_name'];
            $address = $_POST['store_address'];
            $email = $_POST['store_email'];
            $id_role = $_POST['id_role'];

            $modelo = new UserModel();
            $modelo->editarTienda($id, $name, $address, $email, $id_role);

            header('Location: /usuarios?sucess=updated');
            exit;
        }
    }
    public function cambiarEstadoTienda()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_store'];

            $modelo = new UserModel();
            $tienda = $modelo->obtenerTiendaPorId($id);

            if ($tienda) {
                $nuevoEstado = $tienda['is_active'] == 1 ? 0 : 1;
                $modelo->cambiarEstadoTienda($id, $nuevoEstado);
                header('Location: /usuarios?success=estado');
            } else {
                header('Location: /usuarios?error=notfound');
            }

            exit;
        }
    }
}