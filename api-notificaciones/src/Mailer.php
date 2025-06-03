<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // Asegúrate de importar SMTP

require_once __DIR__ . '/../vendor/autoload.php';

class Mailer {
    private $mail;
    private $config; // Guardar config para usarla si es necesario

    public function __construct($config) {
        $this->config = $config; // Guardar config
        $this->mail = new PHPMailer(true);

        // Configuración del servidor
        $this->mail->isSMTP();
        $this->mail->Host = $config['host'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $config['username'];
        $this->mail->Password = $config['password'];

        // Usar la constante de PHPMailer es más robusto
        if (isset($config['encryption']) && strtolower($config['encryption']) === 'ssl') {
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Predeterminado a TLS
        }
        // $this->mail->SMTPSecure = $config['encryption']; // Alternativamente, si siempre es 'tls' o 'ssl'

        $this->mail->Port = $config['port'];

        $this->mail->setFrom($config['from'], $config['from_name']);
        $this->mail->CharSet = PHPMailer::CHARSET_UTF8;

        // Configuración de depuración SMTP
        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER; // Nivel de depuración detallado
        // Para capturar la salida de depuración en la respuesta JSON:
        $this->mail->Debugoutput = function($str, $level) use (&$debugLog) {
            // Añadimos $str a una variable que luego incluiremos en la respuesta
            // Nota: $debugLog se pasa por referencia desde el método send()
            // Esta es una forma, otra sería almacenar en una propiedad de la clase.
            // Por simplicidad, pasaremos $debugLog al método send.
        };
    }

    public function send($to, $subject, $body) {
        $debugLog = ""; // Variable para almacenar la salida de depuración

        // Re-asignar Debugoutput aquí para capturar $debugLog por referencia
        $this->mail->Debugoutput = function($str, $level) use (&$debugLog) {
            $debugLog .= $str . "\n";
        };
        // Si prefieres, puedes definir $this->debugLog como una propiedad de la clase,
        // inicializarla como "" en el constructor o al inicio de send(),
        // y luego $this->debugLog .= $str . "\n";

        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            if ($this->mail->send()) {
                return ['success' => true, 'message' => 'Correo enviado con éxito', 'debug' => $debugLog];
            } else {
                // $this->mail->ErrorInfo ya contendrá el último error, el debugLog da el contexto completo.
                return ['success' => false, 'message' => 'Fallo al enviar: ' . $this->mail->ErrorInfo, 'debug' => $debugLog . "\nÚltimo ErrorInfo: " . $this->mail->ErrorInfo];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Excepción atrapada: ' . $e->getMessage(), 'debug' => $debugLog . "\nÚltimo ErrorInfo: " . $this->mail->ErrorInfo . "\nDetalle de Excepción: " . $e->getMessage()];
        }
    }
}