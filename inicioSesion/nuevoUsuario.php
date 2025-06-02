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
                <input class="formulario" type="text" name="nombre" required>
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

            <p>Confirme la contraseña:<br>
                <input class="formulario" type="password" name="confirmar_contrasena" required>
            </p>

            <input class="btn-IS" type="submit" value="Registrarse">
        </form>
    </div>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si las contraseñas coinciden
    if ($_POST['contrasena'] !== $_POST['confirmar_contrasena']) {
        echo "<script>alert('Error: Las contraseñas no coinciden.');</script>";
        exit;
    }

    // Conexión a la base de datos
    $conexion = new mysqli("localhost", "root", "", "usuario");

    if ($conexion->connect_error) {
        echo "<script>alert('Error de conexión: " . $conexion->connect_error . "');</script>";
        exit;
    }

    // Escapar los datos que sí se guardarán
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $correo = $conexion->real_escape_string($_POST['correo']);
    $celular = $conexion->real_escape_string($_POST['celular']);
    $direccion = $conexion->real_escape_string($_POST['direccion']);
    $contrasena = $conexion->real_escape_string($_POST['contrasena']);

    $fecha_registro = date('Y-m-d');

    // Consulta SQL para insertar el nuevo cliente
    $sql = "INSERT INTO cliente (nombre_cliente, correo, celular, direccion, contrasena, fecha_registro) 
            VALUES ('$nombre', '$correo', '$celular', '$direccion', '$contrasena', '$fecha_registro')";

    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Registro exitoso. ¡Bienvenido, $nombre!');</script>";
    } else {
        echo "<script>alert('Error: " . $conexion->error . "');</script>";
    }

    $conexion->close();
}
?>
