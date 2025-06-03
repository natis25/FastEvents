<!DOCTYPE html>

<head>
    <title>Registro de nuevo usuario</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playwrite+DK+Loopet:wght@100..400&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Registro de Usuarios</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <p>Nombre:<br>
                <input class="formulario" type="text" name="nombre"required>
            </p>

            <p>Correo Electrónico:<br>
                <input class="formulario" type="email" name="correo" required>
            </p>

            <p>Celular:<br>
                <input class="formulario" type="number" name="celular" required>
            </p>

            <p>Dirección:<br>
                <input class="formulario" type="text" name="direccion" required>
            </p>

            <p>Contraseña:<br>
                <input class="formulario" type="password" name="contrasena" required>
            </p>

            <input class="btn-IS" type="submit" value="Registrarse">
        </form>
    </div>
</body>

</html>

<?php
// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos (ajusta los parámetros según tu configuración)
    $conexion = new mysqli("localhost", "root", "", "usuario");

    // Verificar conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Recoger y sanitizar los datos del formulario
    $nombre = $conexion->real_escape_string($_POST['nombre_cliente']);
    $correo = $conexion->real_escape_string($_POST['correo']);
    $celular = $conexion->real_escape_string($_POST['celular']);
    $direccion = $conexion->real_escape_string($_POST['direccion']);
    $contrasena = $conexion->real_escape_string($_POST['contrasena']);

    // Hash de la contraseña (recomendado para seguridad)
    //$contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Obtener fecha actual
    $fecha_registro = date('Y-m-d');

    // Preparar la consulta SQL
    $sql = "INSERT INTO cliente (nombre_cliente, correo, celular, direccion, contrasena, fecha_registro) 
            VALUES ('$nombre', '$correo', '$celular', '$direccion', '$contrasena', '$fecha_registro')";

    // Ejecutar la consulta
    if ($conexion->query($sql) === TRUE) {
        echo "Registro exitoso. ¡Bienvenido, $nombre!";
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }

    // Cerrar conexión
    $conexion->close();
}
?>

