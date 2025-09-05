<?php
namespace App\Controllers;
use App\Models\MemberModel;
use Exception;
use PDOException;

class AuthController
{
    private $memberModel;
    public function __construct()
    {
        $this->memberModel = new MemberModel();
    }
    public function showLogin()
    {
        $title = "Inicio de Sesión";
        $layout = "guest";
        view('Auth/loginView', compact('title', 'layout'));
    }

    public function processLogin()
    {
        try {
            $identifier = $_POST['user'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($identifier) || empty($password)) {
                header('Location: /auth/login?error=empty_fields');
                exit;
            }

            if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                header('Location: /auth/login?error=invalid_email');
                exit;
            }

            $account = $this->memberModel->verifyAccount($identifier, $password, 'user');

            if (!$account) {
                $account = $this->memberModel->verifyAccount($identifier, $password, 'store');
            }

            if (!$account) {
                header('Location: /auth/login?error=credentials');
                exit;
            }

            if ((int) ($account['status']) === 0) {
                header('Location: /auth/login?error=inactive');
                exit;
            }

            if ($account) {
                $_SESSION['auth'] = $account;
                $this->memberModel->updateLastLogin($account['id'], $account['type']);
                header('Location: /auth/login?success=login');
                exit;
            }

        } catch (PDOException $error) {
            throw new Exception("Error al procesar el inicio de sesión: " . $error->getMessage());
        }
    }

    public function logout()
    {
        try {
            $_SESSION['user'] = null;
            session_destroy();
            header('Location: /');
            exit;
        } catch (PDOException $error) {
            throw new Exception("Error al cerrar sesión: " . $error->getMessage());
        }
    }
}
