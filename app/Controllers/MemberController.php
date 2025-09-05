<?php
namespace App\Controllers;
use App\Models\MemberModel;
use App\helpers\Controller;
use Exception;
use PDOException;

class MemberController
{
    private $memberModel;
    private $controllerHelper;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->controllerHelper = new Controller();
    }

    private function requireAuth(?int $role = null)
    {
        if (empty($_SESSION['auth'])) {
            header('Location: /auth/login');
            exit;
        }

        if ($role !== null && $_SESSION['auth']['id_role'] != $role) {
            header('Location: /auth/login');
            exit;
        }
    }

    private function redirect(string $path)
    {
        header("Location: $path");
        exit;
    }

    public function showMembers()
    {
        try {
            $this->requireAuth(1); // solo admin

            $page = $_GET['page'] ?? 1;
            $perPage = 6;

            $members = $this->memberModel->getMembers();
            $membersPaginated = $this->controllerHelper->paginate($members, $page, $perPage);

            view('Admin/userView', [
                'title' => "Usuarios",
                'layout' => "main",
                'membersPaginated' => $membersPaginated
            ]);
        } catch (PDOException $error) {
            throw new Exception("Error: " . $error->getMessage());
        }
    }

    public function showDetails()
    {
        try {
            $this->requireAuth();

            $id = $_SESSION['auth']['id'];
            $member = $this->memberModel->getMemberById($id);

            if (!$member) {
                throw new Exception("Usuario no encontrado.");
            }

            view('Admin/ShowDetails', [
                'title' => "Detalles del Usuario",
                'layout' => "main",
                'member' => $member
            ]);
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function addMember()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST')
                return;

            $type = $_POST['id_role'] == 3 ? 'store' : 'user';

            $data = [
                'username' => $_POST['username'] ?? null,
                'email' => $_POST['email'] ?? null,
                'password' => $_POST['password'] ?? null,
                'store_name' => $_POST['store_name'] ?? null,
                'store_address' => $_POST['store_address'] ?? null,
                'store_email' => $_POST['store_email'] ?? null,
                'id_role' => $_POST['id_role'],
            ];

            $this->memberModel->addMember($data, $type);
            $this->redirect('/users/');
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function editMember()
    {
        try {
            $type = !empty($_POST['id_user']) ? 'user' : (!empty($_POST['id_store']) ? 'store' : null);
            if (!$type)
                throw new Exception("Tipo de miembro no especificado.");

            $data = [
                'id_user' => $_POST['id_user'] ?? null,
                'id_store' => $_POST['id_store'] ?? null,
                'username' => $_POST['username'] ?? null,
                'email' => $_POST['email'] ?? null,
                'store_name' => $_POST['username'] ?? null,
                'store_email' => $_POST['email'] ?? null,
                'store_address' => $_POST['store_address'] ?? null,
                'id_role' => $_POST['id_role'],
            ];

            $this->memberModel->editMember($data, $type);
            $this->redirect('/users/');
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function changeStatus()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST')
                return;

            $type = $_POST['type'] ?? null;
            $id = $type === 'store' ? ($_POST['id_store'] ?? null) : ($_POST['id_user'] ?? null);
            $status = (int) ($_POST['status'] ?? 0);

            if (!$id || !$type)
                throw new Exception("Datos incompletos.");

            $this->memberModel->toggleMemberStatus($id, $type, $status);
            $this->redirect('/users/');
        } catch (PDOException $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}
