<?php
require_once 'config/db.php';

try {
    $stmt = $conn->query("SELECT * FROM eventos WHERE activo = 1 ORDER BY fecha ASC LIMIT 3");
    $eventos_destacados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $eventos_destacados = [];
    $error = "Error al cargar eventos destacados: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="hero">
    <h1>Bienvenido al Gestor de Eventos</h1>
    <p>Organiza, gestiona y promociona tus eventos de manera sencilla</p>
    <a href="views/eventos/listar.php" class="btn btn-large">Ver todos los eventos</a>
</div>

<?php if(isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<section class="eventos-destacados">
    <h2>Próximos Eventos</h2>
    
    <?php if(count($eventos_destacados) > 0): ?>
        <div class="eventos-grid">
            <?php foreach($eventos_destacados as $evento): ?>
                <div class="evento-card">
                    <h3><?= htmlspecialchars($evento['nombre']) ?></h3>
                    <p><strong>Tipo:</strong> <?= htmlspecialchars($evento['tipo']) ?></p>
                    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($evento['fecha'])) ?></p>
                    <p><strong>Hora:</strong> <?= date('H:i', strtotime($evento['hora'])) ?></p>
                    <p><strong>Lugar:</strong> <?= htmlspecialchars($evento['direccion']) ?></p>
                    <a href="views/eventos/ver.php?id=<?= $evento['id_evento'] ?>" class="btn">Ver detalles</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay eventos próximos por el momento.</p>
    <?php endif; ?>
</section>

<section class="acciones-rapidas">
    <h2>Acciones Rápidas</h2>
    <div class="acciones-grid">
        <a href="views/eventos/crear.php" class="btn btn-large">Crear Nuevo Evento</a>
        <a href="views/eventos/listar.php" class="btn btn-large">Gestionar Eventos</a>
    </div>
</section>
</body>
