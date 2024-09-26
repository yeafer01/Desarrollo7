<?php
// Incluir archivos de conexión y clase Automovil
include 'includes/Database.php';
include 'includes/Automovil.php';

// Crear una instancia de la clase Database y obtener la conexión
$database = new Database();
$db = $database->getConnection();

// Crear una instancia de la clase Automovil
$automovil = new Automovil($db);

// Obtener los datos del formulario
$automovil->color = $_POST['placa'];
$automovil->marca = $_POST['marca'];
$automovil->modelo = $_POST['modelo'];
$automovil->anio = $_POST['anio'];
$automovil->color = $_POST['color'];
$automovil->motor = $_POST['motor'];
$automovil->chasis = $_POST['chasis'];
$automovil->tipo = $_POST['tipo'];


// Registrar el automóvil
if ($automovil->registrar()) {
    echo "Automóvil registrado exitosamente.";
} else {
    echo "Error al registrar el automóvil.";
}
?>
