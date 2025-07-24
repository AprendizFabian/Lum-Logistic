<?php

namespace App\Controllers;

use App\UserModel;

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
            header('Location: Landing/index');
        } else {
            header('Location: /login?error=1');
        }
        exit;
    }
}
