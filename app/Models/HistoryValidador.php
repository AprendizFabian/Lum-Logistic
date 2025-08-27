<?php
namespace App\Models;

class HistoryValidador
{
    private $pdo;

    public function __construct()
    {
        // Variables de entorno con valores por defecto
        $host   = getenv('DB_HOST') ?: 'localhost';
        $port   = getenv('DB_PORT') ?: '3306';   // Puerto MySQL
        $dbname = getenv('DB_NAME') ?: 'lumlogisticdb'; 
        $user   = getenv('DB_USER') ?: 'root';   // Usuario MySQL
        $pass   = getenv('DB_PASS') ?: '';

        // DSN para MySQL
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

        $this->pdo = new \PDO($dsn, $user, $pass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);
    }

    /**
     * Verifica si ya existe un registro con ese EAN y fecha de bloqueo
     */
    public function existeEanOFecha($ean, $blockDate)
    {
        $stmt = $this->pdo->prepare("
            SELECT EXISTS (
                SELECT 1 
                FROM validation_history
                WHERE ean = :ean 
                AND block_date = :block_date
            ) AS existe
        ");
        $stmt->execute([
            ':ean'        => $ean,
            ':block_date' => $blockDate
        ]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Inserta un nuevo registro en validation_history
     */
    public function insertarRegistro(
        $ean,
        $description,
        $expirationDate,
        $blockDate,
        $category,
        $daysLifespan,   // 👈 nuevo campo
        $blockConcept,
        $remarks,
        $idStore
    ) {
        $stmt = $this->pdo->prepare("
            INSERT INTO validation_history (
                ean,
                description,
                expiration_date,
                block_date,
                category,
                days_lifespan,   -- 👈 nuevo campo
                block_concept,
                remarks,
                id_store
            ) VALUES (
                :ean, :description, :expiration_date, :block_date, 
                :category, :days_lifespan, :block_concept, :remarks, :id_store
            )
        ");

        $stmt->execute([
            ':ean'             => $ean,
            ':description'     => $description,
            ':expiration_date' => $expirationDate,
            ':block_date'      => $blockDate,
            ':category'        => $category,
            ':days_lifespan'   => $daysLifespan,  // 👈 se envía al insert
            ':block_concept'   => $blockConcept,
            ':remarks'         => $remarks,
            ':id_store'        => $idStore
        ]);
    }
}
