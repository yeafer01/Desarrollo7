<?php
include_once 'Database.php';
$db = new Database();
$conn = $db->getConnection();

if (isset($_POST['marca'])) {
    $marca = $_POST['marca'];

    // Consulta para obtener los modelos y tipos
    $queryModelos = "SELECT m.modelo_id, m.nombre_modelo 
                     FROM modelo m
                     JOIN marca b ON m.marca_id = b.marca_id
                     WHERE b.nombre_marca = :marca";
    $stmtModelos = $conn->prepare($queryModelos);
    $stmtModelos->bindParam(':marca', $marca);

    if ($stmtModelos->execute()) {
        $modelos = $stmtModelos->fetchAll(PDO::FETCH_ASSOC);

        // Obtener tipos de vehículos asociados a los modelos
        $tipos = [];
        foreach ($modelos as $modelo) {
            $queryTipos = "SELECT t.nombre_tipo 
                           FROM modelo m
                           JOIN tipo t ON m.tipo_id = t.tipo_id
                           WHERE m.modelo_id = :modelo_id";
            $stmtTipos = $conn->prepare($queryTipos);
            $stmtTipos->bindParam(':modelo_id', $modelo['modelo_id']);
            $stmtTipos->execute();
            $tiposModelo = $stmtTipos->fetchAll(PDO::FETCH_ASSOC);
            $tipos[$modelo['modelo_id']] = $tiposModelo;
        }

        // Devuelve los datos en formato JSON
        echo json_encode(['modelos' => $modelos, 'tipos' => $tipos]);
    } else {
        echo json_encode(['error' => 'Error al ejecutar la consulta']);
    }
} else {
    echo json_encode(['error' => 'No se ha recibido la marca']);
}
?>
