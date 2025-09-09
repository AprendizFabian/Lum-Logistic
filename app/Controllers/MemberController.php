<?php
namespace App\Controllers;
use App\Middleware\ErrorHandler;
use App\Models\MemberModel;
use App\Helpers\Controller;
use Exception;

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
        return ErrorHandler::handle(function () {
            $this->requireAuth(1);

            $page = $_GET['page'] ?? 1;
            $perPage = 6;
            $search = $_GET['search'] ?? null;
            $filter = $_GET['filter'] ?? null;

            $members = $this->memberModel->getMembers($filter, $search);
            $cities = $this->memberModel->getCities();
            $membersPaginated = $this->controllerHelper->paginate($members, $page, $perPage);

            view('Admin/userView', [
                'title' => "Usuarios",
                'layout' => "main",
                'membersPaginated' => $membersPaginated,
                'cities' => $cities
            ]);
        });
    }

    public function showDetails()
    {
        return ErrorHandler::handle(function () {
            $this->requireAuth();

            $id = $_SESSION['auth']['id'];
            $member = $this->memberModel->getMemberById($id);

            if (!$member) {
                throw new Exception("Usuario no encontrado.");
            }

            view('Admin/showDetails', [
                'title' => "Detalles del Usuario",
                'layout' => "main",
                'member' => $member
            ]);
        });
    }

    public function addMember()
    {
        return ErrorHandler::handle(function () {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                return;
            }

            $type = $_POST['id_role'] == 3 ? 'store' : 'user';

            if ($type === 'store') {
                $data = [
                    'store_name' => $_POST['store_name'] ?? null,
                    'store_address' => $_POST['store_address'] ?? null,
                    'store_email' => $_POST['store_email'] ?? null,
                    'city_id' => $_POST['city_id'] ?? null,
                    'password' => $_POST['password'] ?? null,
                    'id_role' => $_POST['id_role'],
                ];

                if (!filter_var($data['store_email'], FILTER_VALIDATE_EMAIL)) {
                    $this->redirect('/users/?error=invalid_email');
                }

            } else {
                $data = [
                    'username' => $_POST['username'] ?? null,
                    'email' => $_POST['email'] ?? null,
                    'password' => $_POST['password'] ?? null,
                    'id_role' => $_POST['id_role'],
                ];

                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->redirect('/users/?error=invalid_email');
                }

            }

            $this->memberModel->addMember($data, $type);
            $this->redirect('/users/?success=added');
        });
    }

    public function editMember()
    {
        return ErrorHandler::handle(function () {
            $type = !empty($_POST['id_user']) ? 'user' : (!empty($_POST['id_store']) ? 'store' : null);
            if (!$type) {
                throw new Exception("Tipo de miembro no especificado.");
            }

            $data = [
                'id_user' => $_POST['id_user'] ?? null,
                'id_store' => $_POST['id_store'] ?? null,
                'username' => $_POST['username'] ?? null,
                'email' => $_POST['email'] ?? null,
                'store_name' => $_POST['username'] ?? null,
                'store_email' => $_POST['email'] ?? null,
                'store_address' => $_POST['store_address'] ?? null,
                'city_id' => $_POST['city_id'] ?? null,
                'id_role' => $_POST['id_role'],
            ];

            $this->memberModel->editMember($data, $type);
            $this->redirect('/users/?success=updated');
        });
    }

    public function changeStatus()
    {
        return ErrorHandler::handle(function () {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST')
                return;

            $type = $_POST['type'] ?? null;
            $id = $type === 'store' ? ($_POST['id_store'] ?? null) : ($_POST['id_user'] ?? null);
            $status = (int) ($_POST['status'] ?? 0);

            if (!$id || !$type)
                throw new Exception("Datos incompletos.");

            $this->memberModel->toggleMemberStatus($id, $type, $status);
            $this->redirect('/users/');
        });
    }
}