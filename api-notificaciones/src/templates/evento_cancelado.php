<?php
// src/templates/evento_cancelado.php
// info para evento cancelado: nombre:"nombre de comprador", evento: "nombre del evento", motivo: "vacio o especificar"
return function($data) {
    $nombre = $data['nombre'] ?? 'Usuario';
    $evento = $data['evento'] ?? 'Evento no especificado';
    $motivo = $data['motivo'] ?? 'motivos de fuerza mayor';

    $subject = "🚫 Cancelación del evento: $evento";

    $body = '
        <div style="font-family: \'Segoe UI\', sans-serif; background: linear-gradient(135deg, #ffe0e0, #fff5f5); padding: 30px; border-radius: 12px; max-width: 650px; margin: auto; border: 2px solid #ff5252; box-shadow: 0 6px 12px rgba(0,0,0,0.1); color: #333;">
            <div style="text-align: center;">
                <h1 style="font-size: 26px; color: #d32f2f;">🚫 Evento Cancelado</h1>
            </div>
            
            <div style="margin-top: 20px;">
                <p style="font-size: 18px;">Hola <strong style="color:#c62828;">' . htmlspecialchars($nombre) . '</strong>,</p>
                <p style="font-size: 16px; color: #444;">Lamentamos mucho informarte que el evento <strong style="color: #d84315;">' . htmlspecialchars($evento) . '</strong> ha sido cancelado por <em>' . htmlspecialchars($motivo) . '</em>.</p>
            </div>

            <div style="margin-top: 20px; background-color: #fff3f3; padding: 20px; border-left: 6px solid #ff5252; border-radius: 8px;">
                <p style="font-size: 16px; color: #555;"><strong>🎁 Reembolso:</strong> Se realizará automáticamente a tu método de pago en los próximos días.</p>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <p style="font-size: 14px; color: #777;">Agradecemos tu comprensión y esperamos verte en futuros eventos.</p>
                <p style="font-size: 14px; color: #777;">Si tienes preguntas, <a href="mailto:soporte@eventos.com" style="color: #c62828; text-decoration: underline;">contacta a nuestro equipo de soporte</a>.</p>
            </div>
        </div>
    ';

    return [
        'subject' => $subject,
        'body' => $body
    ];
};
