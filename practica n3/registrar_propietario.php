<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Propietario</title>
    <link rel="stylesheet" href="css/css.css"> 
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
    <a href="registrar_automovil.php" class="nav__button">Ver automóviles</a>

    </nav>

    <h2>Registro de Propietario</h2>

    <form action="http://localhost/proyectos/FernandoBarrios/registrar_propietario.php" method="POST">
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" required>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" required>

        <input type="submit" value="Registrar">
    </form>

</body>
</html>
<?php

// Incluir archivo de conexión
include 'includes/Database.php'; // Asegúrate de que el path es correcto

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $cedula = htmlspecialchars(strip_tags($_POST['cedula']));
    $nombre = htmlspecialchars(strip_tags($_POST['nombre']));
    $apellido = htmlspecialchars(strip_tags($_POST['apellido']));
    $telefono = htmlspecialchars(strip_tags($_POST['telefono']));

    // Preparar consulta SQL
    $query = "INSERT INTO propietario (cedula, nombre, apellido, telefono) VALUES (:cedula, :nombre, :apellido, :telefono)";
    $stmt = $db->prepare($query);

    // Vincular parámetros
    $stmt->bindParam(':cedula', $cedula);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido', $apellido);
    $stmt->bindParam(':telefono', $telefono);

    // Ejecutar consulta
    if ($stmt->execute()) {
        echo "<p>Propietario registrado con éxito.</p>";
    } else {
        echo "<p>Error al registrar el propietario: </p>";
    }
} else {
    die('Formulario no enviado.');
}
?>
