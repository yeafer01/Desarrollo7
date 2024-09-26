<?php
class Automovil {
    private $conn; // Conexión a la base de datos
    private $table_name = "automoviles"; // Nombre de la tabla

    // Propiedades de la clase
    public $id;
    public $marca;
    public $modelo;
    public $anio;
    public $color;
    public $motor;
    public $chasis;
    public $tipo;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para eliminar un automóvil
    public function eliminar($id) {
        $consulta = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($consulta);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute(); // Retorna el resultado de la ejecución
    }

    // Método para registrar un nuevo automóvil
    public function registrar() {
        // Query para insertar un nuevo automóvil
        $query = "INSERT INTO " . $this->table_name . " (marca, modelo, anio, color, motor, chasis, tipo) 
                  VALUES (:marca, :modelo, :anio, :color, :motor, :chasis, :tipo)";

        // Preparar la declaración
        $stmt = $this->conn->prepare($query);

        // Limpiar los datos para evitar inyección SQL
        $this->marca = htmlspecialchars(strip_tags($this->marca));
        $this->modelo = htmlspecialchars(strip_tags($this->modelo));
        $this->anio = htmlspecialchars(strip_tags($this->anio));
        $this->color = htmlspecialchars(strip_tags($this->color));
        $this->motor = htmlspecialchars(strip_tags($this->motor));
        $this->chasis = htmlspecialchars(strip_tags($this->chasis));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));

        // Enlazar los parámetros
        $stmt->bindParam(":marca", $this->marca);
        $stmt->bindParam(":modelo", $this->modelo);
        $stmt->bindParam(":anio", $this->anio, PDO::PARAM_INT); // Asegúrate de que el año sea un entero
        $stmt->bindParam(":color", $this->color);
        $stmt->bindParam(":motor", $this->motor);
        $stmt->bindParam(":chasis", $this->chasis);
        $stmt->bindParam(":tipo", $this->tipo);

        // Ejecutar la declaración
        return $stmt->execute(); // Retorna el resultado de la ejecución
    }
}
?>
