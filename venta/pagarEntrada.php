<?php
// Obtener parámetros
$idEvento = $_GET['idEvento'] ?? null;
$idCliente = $_GET['idCliente'] ?? null;
$idVenta = $_GET['idVenta'] ?? null;
$costo = $_GET['costo'] ?? null;

if (!$idEvento || !$idCliente || !$idVenta || !$costo) {
    header("Location: inicioCliente.php?id=" . $idCliente);
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Transacción</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .costo {
            font-size: 24px;
            text-align: center;
            margin: 20px 0;
            color: #2ecc71;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        .mensaje-exito {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 4px;
            display: none;
        }
    </style>
</head>
<body>
    <h1>Realizar transacción</h1>
    <div class="costo">Monto a pagar: $<?= number_format($costo, 2) ?></div>
    
    <div class="form-group">
        <label for="tarjeta">Ingresar tarjeta:</label>
        <input type="text" id="tarjeta" placeholder="Número de tarjeta">
    </div>
    
    <button class="btn" onclick="confirmarPago()">Confirmar</button>
    
    <div class="mensaje-exito" id="mensajeExito"></div>

    <script>
        async function confirmarPago() {
            const tarjeta = document.getElementById('tarjeta').value;
            const idVenta = <?= $idVenta ?>;
            
            if (!tarjeta) {
                alert('Por favor ingrese el número de tarjeta');
                return;
            }
            
            try {
                // Marcar como pagado en la API
                const response = await fetch('../participantes/participante_api.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id_venta: idVenta
                    })
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Mostrar mensaje de éxito
                    const mensaje = document.getElementById('mensajeExito');
                    mensaje.style.display = 'block';
                    mensaje.innerHTML = `
                        Se realizó el pago desde su cuenta ${tarjeta.slice(-4)} por el monto $<?= number_format($costo, 2) ?><br>
                        Redirigiendo a la página principal...
                    `;
                    
                    // Redirigir después de 3 segundos
                    setTimeout(() => {
                        window.location.href = `../participantes/inicioCliente.php?id=<?= $idCliente ?>`;
                    }, 3000);
                } else {
                    alert('Error al procesar el pago');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar el pago');
            }
        }
    </script>
</body>
</html>