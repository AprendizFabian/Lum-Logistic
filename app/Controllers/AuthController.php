<?php

namespace App\Controllers;
use App\Models\UserModel;

class AuthController
{
    public function showLogin()
    {
        $title = "Inicio de Sesión";
        $layout = "guest";
        view('Auth/loginView', compact('title', 'layout'));
    }

    public function processLogin()
    {
        session_start();

        $user = $_POST['user'] ?? '';
        $password = $_POST['password'] ?? '';

        $usuarioModel = new UserModel();
        $userData = $usuarioModel->verifyUser($user, $password);

        if ($userData) {
            $_SESSION['user'] = $userData;
            $usuarioModel->updateLastLogin($userData['id']);
            header('Location: /catalogo');
        } else {
            header('Location: /login?error=1');
        }
        exit;
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }
}
