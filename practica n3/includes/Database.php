<?php
class Database {
    private $host = "127.0.0.1"; 
    private $port = "3309";
    private $db_name = "gestion_automoviles";
    private $username = "root";  // Nombre de usuario de MySQL
    private $password = "";      // Contraseña de MySQL
    private $conn;

    // Método para obtener la conexión a la base de datos
    public function getConnection() {
        $this->conn = null;

        try {
            // Conexión con puerto especificado
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Modo de error de PDO
        } catch(PDOException $exception) {
            error_log("Error de conexión: " . $exception->getMessage()); // Registro de error
            return false;
        }

        return $this->conn;
    }
}
?>
