<?php
header('Content-Type: application/json');

include_once('../conexion.php');
include_once('../controllers/sesionController.php');

$response = ["success" => false, "error" => ""];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $tipoUsuario = $_POST['tipo_usuario'];

    $sesionController = new SesionController();
    
    if ($sesionController->iniciarSesion($email, $password, $tipoUsuario)) {
        $response["success"] = true;
        $response["redirect"] = ($tipoUsuario == 'cliente') 
            ? "../../participantes/inicioCliente.php.php?id=" . $_SESSION['usuario']['id']
            //? "../manejoCuenta/mostrarCuenta.html"
            : "../../../public/pages/menuOrganizador.html";
    } else {
        $response["error"] = "Credenciales incorrectas";
    }
}

echo json_encode($response);
?>