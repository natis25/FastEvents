<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "localhost";
$username = "root"; // Cambia esto si tu usuario es diferente
$password = "";
$dbname = "usuario"; // Base de datos de usuarios

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_organizador = $_GET['id_organizador'] ?? null;
    
    if ($id_organizador) {
        $sql = "SELECT nombre_organizador FROM organizador WHERE id_organizador = $id_organizador";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo json_encode($row);
        } else {
            echo json_encode(["error" => "Organizador no encontrado"]);
        }
    } else {
        echo json_encode(["error" => "Se requiere id_organizador"]);
    }
}

$conn->close();
?>