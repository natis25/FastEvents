<?php 
require_once '../../config/db.php';
require_once '../../views/layout/header.php';

$id_evento = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id_evento) {
    header('Location: listar.php');
    exit;
}

// Obtener información del evento
try {
    $stmt = $conn->prepare("SELECT * FROM eventos WHERE id_evento = :id_evento AND activo = 1");
    $stmt->bindParam(':id_evento', $id_evento);
    $stmt->execute();
    $evento = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$evento) {
        header('Location: listar.php');
        exit;
    }
} catch(PDOException $e) {
    echo "<div class='alert alert-danger'>Error al obtener el evento: " . $e->getMessage() . "</div>";
    exit;
}
?>

<h2>Cancelar Evento</h2>

<div class="evento-info">
    <h3><?= htmlspecialchars($evento['nombre']) ?></h3>
    <p><strong>Tipo:</strong> <?= htmlspecialchars($evento['tipo']) ?></p>
    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($evento['fecha'])) ?></p>
    <p><strong>Hora:</strong> <?= date('H:i', strtotime($evento['hora'])) ?></p>
    <p><strong>Lugar:</strong> <?= htmlspecialchars($evento['direccion']) ?></p>
    <p><strong>Cupos disponibles:</strong> <?= $evento['cupos'] ?></p>
</div>

<div class="confirmacion-cancelar">
    <h3>¿Estás seguro que deseas cancelar este evento?</h3>
    <p>Esta acción no podrá deshacerse y notificará a todos los participantes.</p>
    
    <form action="../../api/eventos_delete.php" method="post">
        <input type="hidden" name="id_evento" value="<?= $evento['id_evento'] ?>">
        
        <div class="form-group">
            <label for="motivo">Motivo de cancelación (opcional):</label>
            <textarea id="motivo" name="motivo" rows="4"></textarea>
        </div>
        
        <div class="acciones">
            <a href="ver.php?id=<?= $evento['id_evento'] ?>" class="btn">Volver al evento</a>
            <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
        </div>
    </form>
</div>

<?php require_once '../../views/layout/footer.php'; ?>