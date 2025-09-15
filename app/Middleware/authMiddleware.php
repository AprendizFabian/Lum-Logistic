<?php
namespace App\Middleware;

class AuthMiddleware
{
    public static function requireAuth(?int $role = null)
    {
        if (empty($_SESSION['auth'])) {
            header('Location: /auth/login');
            exit;
        }

        if ($role !== null && $_SESSION['auth']['id_role'] != $role) {
            header('Location: /auth/unauthorized');
            exit;
        }
    }
}
