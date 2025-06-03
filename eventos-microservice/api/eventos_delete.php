<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$id_evento = isset($_GET['id']) ? $_GET['id'] : null;

try {
    if ($id_evento) {
        // Borrado lógico
        $stmt = $conn->prepare("UPDATE eventos SET activo = 0 WHERE id_evento = :id_evento");
        $stmt->bindParam(':id_evento', $id_evento);
        $stmt->execute();

        header('Location: /FastEvents/eventos-microservice/views/eventos/listar.php');
        exit;
    } else {
        echo json_encode(['error' => 'ID de evento no proporcionado']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>