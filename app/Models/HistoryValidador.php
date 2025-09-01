<?php
namespace App\Models;

class HistoryValidador
{
    private $pdo;

    public function __construct()
    {
        $host   = getenv('DB_HOST') ?: 'localhost';
        $port   = getenv('DB_PORT') ?: '3306'; 
        $dbname = getenv('DB_NAME') ?: 'lumlogisticdb'; 
        $user   = getenv('DB_USER') ?: 'root';   
        $pass   = getenv('DB_PASS') ?: '';
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

        $this->pdo = new \PDO($dsn, $user, $pass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);
    }

public function existeEanOFecha($ean, $blockDate, $idStore)
{
    $sql = "SELECT COUNT(*) FROM validation_history
            WHERE ean = :ean AND block_date = :block_date AND id_store = :id_store";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':ean' => $ean,
        ':block_date' => $blockDate,
        ':id_store' => $idStore
    ]);
    return $stmt->fetchColumn() > 0;
}


 
    public function insertarRegistro(
        $ean,
        $description,
        $expirationDate,
        $blockDate,
        $category,
        $daysLifespan,   
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
                days_lifespan,   
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
            ':days_lifespan'   => $daysLifespan,  
            ':block_concept'   => $blockConcept,
            ':remarks'         => $remarks,
            ':id_store'        => $idStore
        ]);
    }
}
