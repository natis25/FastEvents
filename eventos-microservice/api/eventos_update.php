<?php
require_once '../config/db.php';

try {
    $stmt = $conn->prepare("UPDATE eventos SET 
        nombre = :nombre, 
        tipo = :tipo, 
        precio = :precio, 
        capacidad = :capacidad, 
        cupos = :cupos, 
        direccion = :direccion, 
        fecha = :fecha, 
        hora = :hora, 
        id_organizador = :id_organizador 
        WHERE id_evento = :id_evento");

    $stmt->bindParam(':id_evento', $_POST['id_evento']);
    $stmt->bindParam(':nombre', $_POST['nombre']);
    $stmt->bindParam(':tipo', $_POST['tipo']);
    $stmt->bindParam(':precio', $_POST['precio']);
    $stmt->bindParam(':capacidad', $_POST['capacidad']);
    $stmt->bindParam(':cupos', $_POST['cupos']);
    $stmt->bindParam(':direccion', $_POST['direccion']);
    $stmt->bindParam(':fecha', $_POST['fecha']);
    $stmt->bindParam(':hora', $_POST['hora']);
    $stmt->bindParam(':id_organizador', $_POST['id_organizador']);

    $stmt->execute();


    header('Location: /FastEvents/eventos-microservice/views/eventos/listar.php');
    exit;
} catch (PDOException $e) {
    echo "Error al actualizar: " . $e->getMessage();
}
