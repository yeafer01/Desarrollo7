<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Automóviles</title>
    <link rel="stylesheet" href="css/css.css"> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head>
<body>
    <nav>
        <a href="buscar_automovil.php" class="nav__button">Ver automóviles</a>
        <a href="registrar_propietario.php" class="nav__button">Registrar propietario</a>
    </nav>

    <div>
        <h2>Registrar Automóvil</h2>
        <form action="procesar_registro.php" method="post">
            <label for="placa">Placa:</label>
            <input type="text" id="placa" name="placa" required><br>

            <label for="marca">Marca:</label>
            <select id="marca" name="marca" required>
                <option value="">Selecciona una marca</option>
                <option value="Toyota">Toyota</option>
                <option value="Ford">Ford</option>
                <option value="Chevrolet">Chevrolet</option>
                <!-- Puedes generar dinámicamente estas opciones desde la base de datos -->
            </select><br>

            <label for="modelo">Modelo:</label>
            <select id="modelo" name="modelo" required>
                <option value="">Selecciona un modelo</option>
                <!-- Los modelos se cargarán dinámicamente con AJAX -->
            </select><br>

            <label for="anio">Año:</label>
            <input type="number" id="anio" name="anio" required><br>

            <label for="color">Color:</label>
            <input type="text" id="color" name="color" required><br>

            <label for="motor">Número de motor:</label>
            <input type="text" id="motor" name="motor" required><br>

            <label for="chasis">Número de chasis:</label>
            <input type="text" id="chasis" name="chasis" required><br>

            <label for="tipo">Tipo de vehículo:</label>
            <select id="tipo" name="tipo" required>
                <option value="">Selecciona un tipo</option>
                <!-- Los tipos se cargarán dinámicamente con AJAX -->
            </select><br>

            <label for="cedula_propietario">Cédula del propietario:</label>
            <input type="text" id="cedula_propietario" name="cedula_propietario" required><br>

            <input type="submit" value="Registrar">
        </form>
    </div>
    <footer>
        <p>Nombre: Fernando Barrios</p>
        <p>Cédula: 8-1002-1207</p>
    </footer>

    <script>
$(document).ready(function() {
    // Cargar modelos y tipos al seleccionar una marca
    $('#marca').change(function() {
        var marca = $(this).val();
        
        // Cargar modelos asociados a la marca seleccionada
        if (marca) {
            $.ajax({
                url: 'includes/obtener_modelos.php',
                type: 'POST',
                data: {marca: marca},
                dataType: 'json',
                success: function(data) {
                    $('#modelo').empty(); 
                    $('#tipo').empty(); 
                    $('#modelo').append('<option value="">Selecciona un modelo</option>');
                    $('#tipo').append('<option value="">Selecciona un tipo</option>');

                    $.each(data.modelos, function(index, item) {
                        $('#modelo').append('<option value="' + item.modelo_id + '">' + item.nombre_modelo + '</option>');
                    });

                    $('#modelo').change(function() {
                        var modelo_id = $(this).val();
                        $('#tipo').empty(); 
                        $('#tipo').append('<option value="">Selecciona un tipo</option>'); 

                        if (modelo_id) {
                            $.each(data.tipos[modelo_id], function(index, tipo) {
                                $('#tipo').append('<option value="' + tipo.nombre_tipo + '">' + tipo.nombre_tipo + '</option>');
                            });
                        }
                    });
                },
                error: function() {
                    alert('Error al cargar los modelos');
                }
            });
        }
    });
});

    </script>
</body>
</html>
