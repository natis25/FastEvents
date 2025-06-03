<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Disponibles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .eventos-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .evento-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .evento-nombre {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .evento-detalle {
            font-size: 16px;
            margin-bottom: 8px;
            color: #555;
        }
        .evento-precio {
            font-size: 20px;
            font-weight: bold;
            color: #2ecc71;
            margin: 15px 0;
        }
        .comprar-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .comprar-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="eventos-container">
        <h1>Eventos Disponibles</h1>
        <div id="eventos-list"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener eventos futuros
            fetch('eventos_api.php')
                .then(response => response.json())
                .then(eventos => {
                    const eventosList = document.getElementById('eventos-list');
                    
                    if (eventos.length === 0) {
                        eventosList.innerHTML = '<p>No hay eventos disponibles en este momento.</p>';
                        return;
                    }
                    
                    // Procesar cada evento
                    eventos.forEach(evento => {
                        // Obtener nombre del organizador
                        fetch(`organizadores_api.php?id_organizador=${evento.id_organizador}`)
                            .then(response => response.json())
                            .then(organizador => {
                                const eventoCard = document.createElement('div');
                                eventoCard.className = 'evento-card';
                                
                                eventoCard.innerHTML = `
                                    <div class="evento-nombre">${evento.nombre}</div>
                                    <div class="evento-detalle">Organizador: ${organizador.nombre_organizador || 'No disponible'}</div>
                                    <div class="evento-detalle">Tipo: ${evento.tipo}</div>
                                    <div class="evento-precio">$${parseFloat(evento.precio).toFixed(2)}</div>
                                    <div class="evento-detalle">Fecha: ${new Date(evento.fecha).toLocaleDateString('es-ES')}</div>
                                    <div class="evento-detalle">Hora: ${evento.hora}</div>
                                    <div class="evento-detalle">Cupos disponibles: ${evento.cupos_disponibles} de ${evento.capacidad}</div>
                                    <div class="evento-detalle">Dirección: ${evento.direccion}</div>
                                    <button class="comprar-btn" onclick="comprarEvento(${evento.id})">Comprar</button>
                                `;
                                
                                eventosList.appendChild(eventoCard);
                            })
                            .catch(error => {
                                console.error('Error al obtener organizador:', error);
                            });
                    });
                })
                .catch(error => {
                    console.error('Error al obtener eventos:', error);
                    document.getElementById('eventos-list').innerHTML = '<p>Error al cargar los eventos. Por favor, intente más tarde.</p>';
                });
        });
        
        function comprarEvento(idEvento) {
            // Aquí podrías redirigir a una página de compra o mostrar un modal
            // window.location.href = `comprar.php?id_evento=${idEvento}`;
            const urlParams = new URLSearchParams(window.location.search);
            const idCliente = urlParams.get('id');
    
            if (idCliente) {
                window.location.href = `comprarEntradas.php?idEvento=${idEvento}&idCliente=${idCliente}`;
            } else {
                alert('No se encontró información del cliente');
            }
        }
    </script>
</body>
</html>