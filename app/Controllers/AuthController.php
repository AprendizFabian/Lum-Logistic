<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController
{
    public function showLogin()
    {
        $title = "Inicio de Sesion";
        $layout = "guest";
        view('Auth/login', compact('title', 'layout'));
    }
    public function processLogin()
    {
        $user = $_POST['user'] ?? '';
        $password = $_POST['password'] ?? '';

        $usuarioModel = new UserModel();

        if ($usuarioModel->verifyUser($user, $password)) {
            $_SESSION['user'] = $user;
            header('Location: Auth/catalog');
        } else {
            header('Location: /login?error=1');
        }
        exit;
    }
}
