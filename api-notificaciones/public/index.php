<?php
// CORS headers
header("Access-Control-Allow-Origin: *"); // Para desarrollo está bien, en producción considera cambiar '*' por tu dominio frontend específico.
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Es mejor ser específico con los métodos que permites. '*' es muy permisivo.
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Común y generalmente seguro.
header("Access-Control-Max-Age: 86400"); // Cache de preflight, está bien.

// Responder a peticiones OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // Sin contenido, correcto.
    exit;
}

// Resto del código:
header("Content-Type: application/json"); // Correcto para la respuesta.

require_once __DIR__ . '/../src/Mailer.php'; // Asegúrate de que la ruta es correcta.
$config = require __DIR__ . '/../src/config.php'; // Asegúrate de que la ruta es correcta y este archivo devuelve el array de configuración.

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método no permitido, correcto.
    echo json_encode(['success' => false, 'error' => 'Método no permitido']); // Es buena práctica mantener una estructura de respuesta consistente.
    exit;
}

$input = file_get_contents('php://input');
if ($input === false) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No se pudieron leer los datos de entrada']);
    exit;
}

$data = json_decode($input, true);

// Verificar si json_decode falló
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'JSON mal formado: ' . json_last_error_msg()]);
    exit;
}

// Validación de datos requeridos
if (!isset($data['to'], $data['tipo'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Faltan datos requeridos: to, tipo']);
    exit;
}


$templatePath = __DIR__ . '/../src/templates/' . $data['tipo'] . '.php';
$template = require $templatePath;
$templateData = $template($data); // Retorna array con subject y body


$subject = $templateData['subject'];
$body = $templateData['body'];




// Validación adicional (opcional pero recomendada)
if (!filter_var($data['to'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'La dirección de correo electrónico del destinatario no es válida.']);
    exit;
}



try {
    $mailer = new Mailer($config); // Asumiendo que Mailer y config están correctos.
    $result = $mailer->send($data['to'], $subject, $body);

    // El resultado de $mailer->send() ya incluye 'success' y 'message' o 'error'.
    if ($result['success']) {
        http_response_code(200);
    } else {
        // PHPMailer puede dar errores por varias razones (autenticación, conexión, destinatario inválido después de la validación inicial, etc.)
        // Un error 500 (Error Interno del Servidor) es común si el fallo es del servidor al intentar enviar.
        // Un error 400 (Solicitud incorrecta) podría ser más apropiado si el fallo se debe a datos que, aunque presentes, son incorrectos de una manera que PHPMailer detecta.
        // Ya que $result['message'] contendrá $mail->ErrorInfo, un 500 es generalmente apropiado para fallos de envío.
        http_response_code(500);
    }
    echo json_encode($result);

} catch (Exception $e) {
    // Captura excepciones que puedan ocurrir durante la instanciación de Mailer o cualquier otro imprevisto.
    http_response_code(500);
    error_log("Error en API de correo: " . $e->getMessage()); // Es bueno loguear el error en el servidor.
    echo json_encode(['success' => false, 'error' => 'Ocurrió un error inesperado en el servidor.']);
}