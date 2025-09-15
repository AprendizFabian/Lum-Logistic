<?php
namespace App\Middleware;

use Exception;
use PDOException;

class ErrorHandler
{
    public static function handle(callable $callback)
    {
        try {
            return $callback();
        } catch (PDOException $e) {
            error_log("[DB ERROR] " . $e->getMessage());
            self::renderError("Ocurrió un error en la base de datos." . $e->getMessage());
        } catch (Exception $e) {
            error_log("[APP ERROR] " . $e->getMessage());
            self::renderError("Ocurrió un error inesperado." . $e->getMessage());
        }
    }

    private static function renderError(string $message)
    {
        if (php_sapi_name() === 'cli') {
            echo $message . PHP_EOL;
        } else {
            view('errors/errorGeneral', ['title' => "Error", 'layout' => "main", 'message' => $message]);
        }
        exit;
    }
}
