<?php
header('Content-Type: application/json');
require_once '../config/db.php';

try {
    $stmt = $conn->query("SELECT * FROM eventos WHERE activo = 1");
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($eventos);
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>