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
        // ¡IMPORTANTE!
        session_start(); // <-- Sin esto, $_SESSION no funciona

        $user = $_POST['user'] ?? '';
        $password = $_POST['password'] ?? '';

        $usuarioModel = new UserModel();
        $userData = $usuarioModel->verifyUser($user, $password);

        if ($userData) {
            $_SESSION['user'] = $userData; 
            // Solo para depurar
            // echo '<pre>'; print_r($_SESSION); echo '</pre>';
            header('Location: /catalogo');
        } else {
            header('Location: /login?error=1');
        }
        exit;
    }
}
