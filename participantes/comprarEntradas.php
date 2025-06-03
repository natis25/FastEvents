<?php
// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener parámetros con validación
$idEvento = filter_var($_GET['idEvento'] ?? null, FILTER_VALIDATE_INT);
$idCliente = filter_var($_GET['idCliente'] ?? null, FILTER_VALIDATE_INT);

// if (!$idEvento || !$idCliente) {
//     header("Location: inicioCliente.php?id=" . $idCliente);
//     exit();
// }

// Función mejorada para llamar a la API
function callAPI($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['error' => $error];
    }
    
    curl_close($ch);
    return json_decode($response, true);
}

// Obtener datos del evento específico
$evento = callAPI("http://".$_SERVER['HTTP_HOST']."/FastEvents/participantes/eventos_api.php?id=" . $idEvento);

// Manejo de errores
if (isset($evento['error'])) {
    die("Error al obtener datos del evento: " . $evento['error']);
}

if (empty($evento)) {
    header("Location: inicioCliente.php?id=" . $idCliente);
    exit();
}

// Verificar datos esenciales
$requiredFields = ['nombre', 'precio', 'cupos_disponibles', 'capacidad', 'tipo', 'fecha', 'hora', 'direccion'];
foreach ($requiredFields as $field) {
    if (!isset($evento[$field])) {
        die("El evento no contiene el campo requerido: " . $field);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprar Entradas - <?= htmlspecialchars($evento['nombre']) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .evento-header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0;
            font-size: 28px;
        }
        .precio {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin: 15px 0;
        }
        .form-container {
            background-color: white;
            padding: 25px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 16px;
        }
        .costo-total {
            font-size: 22px;
            text-align: center;
            margin: 25px 0;
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 4px;
        }
        .btn-pagar {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            width: 100%;
            font-size: 18px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-pagar:hover {
            background-color: #0069d9;
        }
        .evento-info {
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 8px;
            font-size: 16px;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="evento-header">
        <h1><?= htmlspecialchars($evento['nombre']) ?></h1>
    </div>
    
    <div class="form-container">
        <div class="evento-info">
            <div class="info-item">
                <span class="info-label">Tipo:</span> <?= htmlspecialchars($evento['tipo']) ?>
            </div>
            <div class="info-item">
                <span class="info-label">Fecha:</span> <?= date('d/m/Y', strtotime($evento['fecha'])) ?>
            </div>
            <div class="info-item">
                <span class="info-label">Hora:</span> <?= htmlspecialchars($evento['hora']) ?>
            </div>
            <div class="info-item">
                <span class="info-label">Dirección:</span> <?= htmlspecialchars($evento['direccion']) ?>
            </div>
            <div class="info-item">
                <span class="info-label">Cupos disponibles:</span> 
                <?= htmlspecialchars($evento['cupos_disponibles']) ?> / <?= htmlspecialchars($evento['capacidad']) ?>
            </div>
        </div>
        
        <div class="precio">Precio por entrada: $<?= number_format($evento['precio'], 2) ?></div>
        
        <form id="compraForm">
            <input type="hidden" id="idEvento" value="<?= $idEvento ?>">
            <input type="hidden" id="idCliente" value="<?= $idCliente ?>">
            <input type="hidden" id="precioUnitario" value="<?= $evento['precio'] ?>">
            
            <div class="form-group">
                <label for="cantidad">Número de entradas:</label>
                <input type="number" id="cantidad" min="1" max="<?= $evento['cupos_disponibles'] ?>" value="1">
            </div>
            
            <div class="costo-total" id="costoTotal">Costo total: $<?= number_format($evento['precio'], 2) ?></div>
            
            <button type="button" class="btn-pagar" onclick="procesarCompra()">Proceder al pago</button>
        </form>
    </div>

    <script>
        // Calcular costo total al cambiar la cantidad
        document.getElementById('cantidad').addEventListener('input', function() {
            const cantidad = parseInt(this.value) || 0;
            const precio = parseFloat(document.getElementById('precioUnitario').value);
            const costoTotal = cantidad * precio;
            
            document.getElementById('costoTotal').textContent = `Costo total: $${costoTotal.toFixed(2)}`;
        });
        
        async function procesarCompra() {
            const idEvento = document.getElementById('idEvento').value;
            const idCliente = document.getElementById('idCliente').value;
            const cantidad = document.getElementById('cantidad').value;
            const precio = parseFloat(document.getElementById('precioUnitario').value);
            const costoTotal = cantidad * precio;
            
            // Validar cantidad
            if (cantidad < 1) {
                alert('Por favor seleccione al menos 1 entrada');
                return;
            }
            
            try {
                // Crear venta
                const ventaResponse = await fetch('../venta/venta_api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                
                const ventaData = await ventaResponse.json();
                
                if (ventaData.status === 'success') {
                    const idVenta = ventaData.id_venta;
                    
                    // Crear participantes (una por cada entrada)
                    const participantesPromises = [];
                    for (let i = 0; i < cantidad; i++) {
                        participantesPromises.push(
                            fetch('participante_api.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id_evento: idEvento,
                                    id_venta: idVenta,
                                    id_cliente: idCliente
                                })
                            })
                        );
                    }
                    
                    // Esperar a que todas las creaciones de participantes terminen
                    await Promise.all(participantesPromises);
                    
                    // Redirigir a página de pago
                    window.location.href = `../venta/pagarEntrada.php?idEvento=${idEvento}&idCliente=${idCliente}&idVenta=${idVenta}&costo=${costoTotal}`;
                } else {
                    alert('Error al crear la venta: ' + (ventaData.message || ''));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la compra. Por favor, intente nuevamente.');
            }
        }
    </script>
</body>
</html>