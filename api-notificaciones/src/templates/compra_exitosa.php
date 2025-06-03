<?php
// para esta evento: "nombre del evento", nombre:"nombre comprador", fecha: "fecha del evento", precio: "precio del evento"
return function($data) {
    $precio = $data['precio'] ?? '$$';
    return [
        'subject' => "🎟️ Compra Exitosa: {$data['evento']}",
        'body' => '
            <div style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; border-radius: 10px; max-width: 600px; margin: auto; border: 1px solid #ddd;">
                <div style="text-align: center;">
                    <h2 style="color:rgb(55, 219, 165);">¡Gracias por tu compra, ' . htmlspecialchars($data['nombre']) . '!</h2>
                    <p style="font-size: 16px; color: #333;">Tu compra fue procesada con éxito.</p>
                </div>
                <div style="background-color: #ffffff; padding: 20px; border-radius: 8px; margin-top: 20px;">
                    <p style="font-size: 16px; color: #333;"><strong>Evento:</strong> ' . htmlspecialchars($data['evento']) . '</p>
                    <p style="font-size: 16px; color: #333;"><strong>Fecha:</strong> ' . htmlspecialchars($data['fecha']) . '</p>
                    <p style="font-size: 16px; color: #333;"><strong>Precio:</strong> <span style="background-color: #e0f7fa; padding: 5px 10px; border-radius: 5px; font-weight: bold;">' . htmlspecialchars($precio) . '</span></p>
                </div>
                <div style="text-align: center; margin-top: 30px;">
                    <p style="color: #777; font-size: 14px;">🎉 ¡Disfruta del evento!<br>Gracias por confiar en nosotros.</p>
                </div>
            </div>
        '
    ];
};
