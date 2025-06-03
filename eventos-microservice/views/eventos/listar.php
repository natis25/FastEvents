
<?php require_once '../../config/db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="../../../style.css">
</head>
<body>
<h1>Listado de Eventos</h1>
<a href="crear.php" class="btn">Nuevo Evento</a>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Precio</th>
            <th>Capacidad</th>
            <th>Cupos</th>
            <th>Dirección</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Organizador</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $stmt = $conn->query("SELECT * FROM eventos WHERE activo = 1");
        while ($evento = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$evento['nombre']}</td>";
            echo "<td>{$evento['tipo']}</td>";
            echo "<td>{$evento['precio']}</td>";
            echo "<td>{$evento['capacidad']}</td>";
            echo "<td>{$evento['cupos']}</td>";
            echo "<td>{$evento['direccion']}</td>";
            echo "<td>{$evento['fecha']}</td>";
            echo "<td>{$evento['hora']}</td>";
            echo "<td>{$evento['id_organizador']}</td>";
            echo "<td>
                    <a href='editar.php?id={$evento['id_evento']}' class='btn'>Editar</a>
                    <a href='#' onclick='confirmarEliminar({$evento['id_evento']})' class='btn btn-danger'>Cancelar</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>
</body>

<script>
function confirmarEliminar(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este evento?')) {
        window.location.href = `../../api/eventos_delete.php?id=${id}`;
    }
}
</script>