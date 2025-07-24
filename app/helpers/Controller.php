<?php
namespace App\helpers;

class Controller
{
    public function sanitize(string $value ): string{
        return htmlspecialchars(trim(string: $value), ENT_QUOTES, 'UTF-8');
    }
}