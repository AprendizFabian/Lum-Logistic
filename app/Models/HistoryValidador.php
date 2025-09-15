<?php
namespace App\Models;

use App\Database;
use PDO;

class HistoryValidador
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }
    public function existeEanOFecha(string $ean, string $blockDate, int $idStore): bool
    {
        $sql = "SELECT 1 
                FROM validation_history
                WHERE ean = :ean 
                  AND block_date = :block_date 
                  AND id_store = :id_store
                LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':ean' => $ean,
            ':block_date' => $blockDate,
            ':id_store' => $idStore
        ]);

        return (bool) $stmt->fetchColumn();
    }
    public function insertarRegistro(
        string $ean,
        string $sync_id,
        string $descripcion,
        string $fechaVencimiento,
        string $fechaBloqueo,
        string $categoria,
        int $diasVidaUtil,
        string $conceptoBloqueo,
        string $estado,
        int $idStore
    ): bool {
        $sql = "INSERT INTO validation_history 
            (ean, sync_id, description, expiration_date, block_date, category, days_lifespan, block_concept, remarks, id_store) 
            VALUES 
            (:ean, :sync_id, :description, :expiration_date, :block_date, :category, :days_lifespan, :block_concept, :remarks, :id_store)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':ean' => $ean,
            ':sync_id' => $sync_id,
            ':description' => $descripcion,
            ':expiration_date' => $fechaVencimiento,
            ':block_date' => $fechaBloqueo,
            ':category' => $categoria,
            ':days_lifespan' => $diasVidaUtil,
            ':block_concept' => $conceptoBloqueo,
            ':remarks' => $estado,
            ':id_store' => $idStore
        ]);
    }

    public function insertarRegistros(array $registros): int
    {
        if (empty($registros)) {
            return 0;
        }

        $sql = "INSERT INTO validation_history 
            (ean, sync_id, description, expiration_date, block_date, category, days_lifespan, block_concept, remarks, id_store) 
            VALUES ";

        $placeholders = [];
        $values = [];
        $i = 0;

        foreach ($registros as $registro) {
            $placeholders[] = "(" . implode(",", array_fill(0, 10, "?")) . ")";
            foreach ($registro as $valor) {
                $values[] = $valor;
            }
            $i++;
        }

        $sql .= implode(",", $placeholders);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);

        return $stmt->rowCount();
    }
}
