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

public function processLogin() {
    session_start();

    $email = trim($_POST['user'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        header('Location: /login?error=credentials');
        exit;
    }

    $usuarioModel = new UserModel();
    $userData = $usuarioModel->verifyUser($email, $password);

    if ($userData) {

        $_SESSION['user'] = $userData;

        if ($userData['type'] === 'store') {
            $_SESSION['id_store'] = $userData['id'];
        } else {
            $_SESSION['id_store'] = null;
        
            $usuarioModel->updateLastLogin($userData['id']);
        }

        header('Location: /catalogo');
        exit;
    } else {
        header('Location: /login?error=credentials');
        exit;
    }
}
 public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }
}
