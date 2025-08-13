<?php
namespace App\Models;
class HistoryValidador
{
    private $pdo;

    public function __construct()
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $port = getenv('DB_PORT') ?: '5432';
        $dbname = getenv('DB_NAME') ?: 'lum';
        $user = getenv('DB_USER') ?: 'postgres';
        $pass = getenv('DB_PASS') ?: '1234';

        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
        $this->pdo = new \PDO($dsn, $user, $pass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
    }

public function existeEanOFecha($ean, $fecha)
{
    $stmt = $this->pdo->prepare("
        SELECT EXISTS (
            SELECT 1 
            FROM lum_prueba.historial_validador
            WHERE ean = :ean AND fecha_bloqueo = :fecha
        ) AS existe
    ");
    $stmt->execute([
        'ean' => $ean,
        'fecha' => $fecha
    ]);
    return (bool) $stmt->fetchColumn();
}




    public function insertarRegistro($descripcion, $diasVidaUtil, $fechaBloqueo, $categoria, $conceptoBloqueo, $estado, $ean)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO lum_prueba.historial_validador (
                descripcion,
                dias_vida_util,
                fecha_bloqueo,
                categoria,
                concepto_bloqueo,
                observacion,
                ean
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $descripcion,
            $diasVidaUtil,
            $fechaBloqueo ?: null,
            $categoria,
            $conceptoBloqueo,
            $estado,
            $ean
        ]);
    }
}
