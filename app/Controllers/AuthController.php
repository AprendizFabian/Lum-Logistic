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
    session_start(); // imprescindible

    $user = $_POST['user'] ?? '';
    $password = $_POST['password'] ?? '';

    $usuarioModel = new UserModel();
    $userData = $usuarioModel->verifyUser($user, $password);

    if ($userData) {
        // Guardamos siempre los campos uniformes
        $_SESSION['user'] = $userData;

        // Opcional: depurar
        // echo '<pre>'; print_r($_SESSION); echo '</pre>';

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
