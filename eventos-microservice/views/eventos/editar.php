<?php 

require_once '../../config/db.php';

$id_evento = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id_evento) {
    header('Location: listar.php');
    exit;
}

$stmt = $conn->prepare("SELECT * FROM eventos WHERE id_evento = :id_evento AND activo = 1");
$stmt->bindParam(':id_evento', $id_evento);
$stmt->execute();
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    header('Location: listar.php');
    exit;
}
?>

<h2>Editar Evento</h2>

<form action="../../api/eventos_update.php" method="post">
    <input type="hidden" name="id_evento" value="<?= $evento['id_evento'] ?>">
    
    <div class="form-group">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?= $evento['nombre'] ?>" required>
    </div>
    
    <div class="form-group">
        <label for="tipo">Tipo:</label>
        <input type="text" id="tipo" name="tipo" value="<?= $evento['tipo'] ?>" required>
    </div>
    
    <div class="form-group">
        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" value="<?= $evento['precio'] ?>" required>
    </div>
    
    <div class="form-group">
        <label for="capacidad">Capacidad:</label>
        <input type="number" id="capacidad" name="capacidad" value="<?= $evento['capacidad'] ?>" required>
    </div>
    
    <div class="form-group">
        <label for="cupos">Cupos disponibles:</label>
        <input type="number" id="cupos" name="cupos" value="<?= $evento['cupos'] ?>" required>
    </div>
    
    <div class="form-group">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?= $evento['direccion'] ?>" required>
    </div>
    
    <div class="form-group">
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" value="<?= $evento['fecha'] ?>" required>
    </div>
    
    <div class="form-group">
        <label for="hora">Hora:</label>
        <input type="time" id="hora" name="hora" value="<?= $evento['hora'] ?>" required>
    </div>
    
    <div class="form-group">
        <label for="id_organizador">ID Organizador:</label>
        <input type="number" id="id_organizador" name="id_organizador" value="<?= $evento['id_organizador'] ?>" required>
    </div>
    
    <button type="submit" class="btn">Actualizar Evento</button>
</form>
