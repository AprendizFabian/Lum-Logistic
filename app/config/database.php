<?php
class Database {
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "lum"; // Cambia esto
    private $user = "postgres"; // Cambia esto
private $password = "1234"; // Cambia esto

    public function getConnection() {
        try {
            $pdo = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->dbname", $this->user, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("SET search_path TO lum_pruaba"); // Usa tu esquema
            return $pdo;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}
