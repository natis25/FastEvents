<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$data = $_POST;

try {
    $stmt = $conn->prepare("INSERT INTO eventos (nombre, tipo, precio, capacidad, cupos, direccion, fecha, hora, id_organizador) 
                            VALUES (:nombre, :tipo, :precio, :capacidad, :cupos, :direccion, :fecha, :hora, :id_organizador)");

    $stmt->bindParam(':nombre', $data['nombre']);
    $stmt->bindParam(':tipo', $data['tipo']);
    $stmt->bindParam(':precio', $data['precio']);
    $stmt->bindParam(':capacidad', $data['capacidad']);
    $stmt->bindParam(':cupos', $data['cupos']);
    $stmt->bindParam(':direccion', $data['direccion']);
    $stmt->bindParam(':fecha', $data['fecha']);
    $stmt->bindParam(':hora', $data['hora']);
    $stmt->bindParam(':id_organizador', $data['id_organizador']);

    $stmt->execute();

    header('Location: /FastEvents/eventos-microservice/views/eventos/listar.php');

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>


