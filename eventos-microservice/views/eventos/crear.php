<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="../../../style.css">
</head>
<body>

<h1>Crear Nuevo Evento</h1>

<form action="../../api/eventos_create.php" method="post">
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
    </div>
    
    <div class="form-group">
        <label for="tipo">Tipo:</label>
        <input type="text" id="tipo" name="tipo" required>
    </div>
    
    <div class="form-group">
        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" required>
    </div>
    
    <div class="form-group">
        <label for="capacidad">Capacidad:</label>
        <input type="number" id="capacidad" name="capacidad" required>
    </div>
    
    <div class="form-group">
        <label for="cupos">Cupos disponibles:</label>
        <input type="number" id="cupos" name="cupos" required>
    </div>
    
    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>
    </div>
    
    <div class="form-group">
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required>
    </div>
    
    <div class="form-group">
        <label for="hora">Hora:</label>
        <input type="time" id="hora" name="hora" required>
    </div>
    
    <div class="form-group">
        <label for="id_organizador">ID Organizador:</label>
        <input type="number" id="id_organizador" name="id_organizador" required>
    </div>
    
    <button type="submit" class="btn">Crear Evento</button>
</form>
</body>