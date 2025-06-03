<?php
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$basedatos = "usuario";

$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Opcional: Configurar el charset si es necesario
$conexion->set_charset("utf8");
?>