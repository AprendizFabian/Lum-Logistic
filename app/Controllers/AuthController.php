<?php

namespace App\Controllers;

class AuthController
{
    public function showLogin()
    {
        require_once __DIR__ . '/../views/login.php';
    }

    public function procesarLogin()
    {
        $usuario = $_POST['usuario'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        $modelo = new \App\Models\UsuarioModel();

        if ($modelo->verificarUsuario($usuario, $contrasena)) {
            header('Location: /index');
        } else {
            header('Location: /login?error=1');
        }
        exit;
    }
}
