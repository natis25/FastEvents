<?php
header('Content-Type: application/json');
require_once '../config/db.php';

$id_evento = isset($_GET['id']) ? $_GET['id'] : null;

try {
    if ($id_evento) {
        $stmt = $conn->prepare("SELECT * FROM eventos WHERE id_evento = :id_evento AND activo = 1");
        $stmt->bindParam(':id_evento', $id_evento);
        $stmt->execute();
        $evento = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($evento) {
            echo json_encode($evento);
        } else {
            echo json_encode(['error' => 'Evento no encontrado']);
        }
    } else {
        echo json_encode(['error' => 'ID de evento no proporcionado']);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>