<?php
namespace App\Models;
use App\Database;
class HistoryValidador
{
    private $pdo;

 public function __construct()
    {
        $this->pdo = Database::getInstance();
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
