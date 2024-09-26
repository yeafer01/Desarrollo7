<?php
include 'includes/Database.php'; // traer la clase de conexión a la BD

$database = new Database();
$db = $database->getConnection(); // conexión a la base de datos

// Clase Automovil
class Automovil {
    private $conn;
    private $table_name = "automoviles";

    // Constructor que recibe la conexión a la BD
    public function __construct($db) {
        $this->conn = $db;
    }

    // Función para buscar automóviles por id o placa
    public function buscar($termino) {
        $consulta = "SELECT a.id, a.placa, m.nombre_modelo, mar.nombre_marca, a.anio, a.color, a.motor, a.chasis, t.nombre_tipo, p.cedula, p.nombre, p.apellido, p.telefono
                     FROM " . $this->table_name . " a
                     INNER JOIN modelo m ON a.modelo_id = m.modelo_id
                     INNER JOIN marca mar ON m.marca_id = mar.marca_id
                     INNER JOIN tipo t ON m.tipo_id = t.tipo_id
                     INNER JOIN propietario p ON a.cliente_cedula = p.cedula
                     WHERE a.id LIKE :termino OR a.placa LIKE :termino"; // Modificada para obtener marca, modelo, tipo, y propietario
                     
        $stmt = $this->conn->prepare($consulta);
        $termino = "%" . htmlspecialchars(strip_tags($termino)) . "%";
        $stmt->bindParam(':termino', $termino);
        $stmt->execute();
        return $stmt;
    }

    public function obtenerTodos() {
        // Consulta SQL para obtener todos los automóviles con JOIN
        $consulta = "SELECT a.id, a.placa, m.nombre_modelo, mar.nombre_marca, a.anio, a.color, a.motor, a.chasis, t.nombre_tipo, p.cedula, p.nombre, p.apellido, p.telefono
                     FROM " . $this->table_name . " a
                     INNER JOIN modelo m ON a.modelo_id = m.modelo_id
                     INNER JOIN marca mar ON m.marca_id = mar.marca_id
                     INNER JOIN tipo t ON m.tipo_id = t.tipo_id
                     INNER JOIN propietario p ON a.cliente_cedula = p.cedula";
                     
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
            <input type="text" id="buscar" name="buscar" placeholder="Ingresa ID o placa">
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
                    <th>Motor</th>
                    <th>Chasis</th>
                    <th>Tipo</th>
                    <th>Cédula Propietario</th>
                    <th>Nombre Propietario</th>
                    <th>Teléfono Propietario</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            <!-- Un while para listar todos los automóviles y sus propietarios -->
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['placa']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_marca']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_modelo']); ?></td>
                    <td><?php echo htmlspecialchars($row['anio']); ?></td>
                    <td><?php echo htmlspecialchars($row['color']); ?></td>
                    <td><?php echo htmlspecialchars($row['motor']); ?></td>
                    <td><?php echo htmlspecialchars($row['chasis']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre_tipo']); ?></td>
                    <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                    <td><?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                    <td class="boton-eliminar">
                        <a href="buscar_automovil.php?eliminar=<?php echo $row['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este automóvil?')">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>Nombre: Fernando Barrios</p>
        <p>Cédula: 8-1002-1207</p>
    </footer>

</body>
</html>
