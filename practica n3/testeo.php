<?php
//esto ayuda a comprobar si se conecta a la bd
include 'includes/Database.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "Conexión exitosa";
} else {
    echo "Error de conexión";
}
