<?php
include 'includes/Database.php'; // trae el php de la bd

$database = new Database();
$db = $database->getConnection(); // conexion a la base

//clase Automovil
class Automovil {
    private $conn;
    private $table_name = "automoviles";

    // Constructor que recibe la bd
    public function __construct($db) {
        $this->conn = $db;
    }

    // Función para buscar automóviles por id o placa
    public function buscar($termino) {
        $consulta = "SELECT * FROM " . $this->table_name . " WHERE id LIKE :termino OR placa LIKE :termino";//consulta
        $stmt = $this->conn->prepare($consulta);
        $termino = "%" . htmlspecialchars(strip_tags($termino)) . "%";
        // Hace un bind para término
        $stmt->bindParam(':termino', $termino);
        $stmt->execute();
        return $stmt;
    }

    public function obtenerTodos() {
        // Consulta SQL para obtener todos los automóviles
        $consulta = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($consulta);
        $stmt->execute();
        return $stmt;
    }

    // Función para eliminar automóviles por ID
    public function eliminar($id) {
        $consulta = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($consulta);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}

$automovil = new Automovil($db);

// Manejar eliminación
if (isset($_GET['eliminar'])) {
    $id_a_eliminar = $_GET['eliminar'];
    if ($automovil->eliminar($id_a_eliminar)) {
        echo "<p>Automóvil eliminado con éxito.</p>";
    } else {
        echo "<p>Error al eliminar el automóvil.</p>";
    }
}

// chequea si se está buscando algo, si no entonces muestra todo
if (isset($_POST['buscar'])) {
    $termino_busqueda = $_POST['buscar'];
    $stmt = $automovil->buscar($termino_busqueda);
} else {
    $stmt = $automovil->obtenerTodos();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Automóviles</title>
    <link rel="stylesheet" href="css/css.css"> 
</head>
<body>
    <nav>
        <a href="registrar_automovil.php" class="nav-button">Registrar Automóvil</a>
    </nav>
    <div>
        <h2>Lista de Automóviles</h2>

        <form action="buscar_automovil.php" method="post">
            <label for="buscar">Buscar por ID o Placa:</label>
            <input type="text" id="buscar" name="buscar" placeholder="Ingresa marca o placa">
            <input type="submit" value="Buscar">
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Color</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            <!-- Un while para listar todos los automóviles -->
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['placa']); ?></td>
                    <td><?php echo htmlspecialchars($row['marca']); ?></td>
                    <td><?php echo htmlspecialchars($row['modelo']); ?></td>
                    <td><?php echo htmlspecialchars($row['anio']); ?></td>
                    <td><?php echo htmlspecialchars($row['color']); ?></td>
                    <td class= "boton-eliminar">
                        <a href="buscar_automovil.php?eliminar=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este automóvil?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
