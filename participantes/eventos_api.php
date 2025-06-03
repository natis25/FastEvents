<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "eventos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener ID del evento si se especifica
    $idEvento = isset($_GET['id']) ? intval($_GET['id']) : null;
    
    if ($idEvento) {
        // Consulta para un evento específico
        $stmt = $conn->prepare("SELECT * FROM eventos WHERE id = ?");
        $stmt->bind_param("i", $idEvento);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $evento = $result->fetch_assoc();
            echo json_encode($evento);
        } else {
            echo json_encode(["error" => "Evento no encontrado"]);
        }
        $stmt->close();
    } else {
        // Consulta para todos los eventos futuros
        $current_date = date('Y-m-d');
        $sql = "SELECT * FROM eventos WHERE fecha >= '$current_date' AND cancelado = 0 ORDER BY fecha ASC";
        
        $result = $conn->query($sql);
        $eventos = [];
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $eventos[] = $row;
            }
        }
        
        echo json_encode($eventos);
    }
}

$conn->close();
?>