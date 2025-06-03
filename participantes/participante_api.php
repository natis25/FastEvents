<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type");

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Participantes";  // Base de datos específica para Participantes

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Método POST para agregar nuevo participante
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $id_evento = $data['id_evento'] ?? null;
    $id_venta = $data['id_venta'] ?? null;
    $id_cliente = $data['id_cliente'] ?? null;
    
    if ($id_evento && $id_venta && $id_cliente) {
        $sql = "INSERT INTO Participantes (id_evento, id_venta, id_cliente, pagado) 
                VALUES ($id_evento, $id_venta, $id_cliente, FALSE)";
        
        if ($conn->query($sql)) {
            echo json_encode([
                "status" => "success",
                "message" => "Participante agregado exitosamente"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al agregar participante: " . $conn->error
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Faltan datos requeridos"
        ]);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id_venta = $data['id_venta'] ?? null;
    
    if ($id_venta) {
        $sql = "UPDATE Participantes SET pagado = TRUE WHERE id_venta = $id_venta";
        
        if ($conn->query($sql)) {
            $affected_rows = $conn->affected_rows;
            echo json_encode([
                "status" => "success",
                "message" => "Participantes actualizados",
                "affected_rows" => $affected_rows
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al actualizar participantes: " . $conn->error
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Se requiere id_venta"
        ]);
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM Participantes";
    $result = $conn->query($sql);
    
    $participantes = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $participantes[] = $row;
        }
    }
    
    echo json_encode($participantes);
}

$conn->close();
?>