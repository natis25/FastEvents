<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Venta";  // Base de datos específica para Venta

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO Venta (fecha, hora) VALUES (CURRENT_DATE(), CURRENT_TIME())";
    
    if ($conn->query($sql)){
        $id_venta = $conn->insert_id;
        echo json_encode([
            "status" => "success",
            "message" => "Venta creada exitosamente",
            "id_venta" => $id_venta
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Error al crear venta: " . $conn->error
        ]);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM Venta";
    $result = $conn->query($sql);
    
    $ventas = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $ventas[] = $row;
        }
    }
    
    echo json_encode($ventas);
}

$conn->close();
?>