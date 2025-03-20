<?php
include('../../BD/ConexionBD.php');
include('../../Nav/header2.php');

if (!isset($_SESSION['id_usuario'])) {
    die("Error: No hay un usuario en sesión.");
}

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$id_usuario = $_SESSION['id_usuario']; // ID del usuario en sesión

// Obtener el historial de compras
$sql = "SELECT sc.id_solicitud, sc.descripcion, sc.total, sc.fecha_registro, sc.estatus,
               scd.id_articulo, a.nombre_articulo, scd.cantidad, scd.subtotal, scd.total AS total_articulo
        FROM solicitud_compra sc
        JOIN solicitud_compra_detalle scd ON sc.id_solicitud = scd.id_solicitud
        JOIN articulos a ON scd.id_articulo = a.id_articulo
        WHERE sc.id_comprador_usuario = ?
        ORDER BY sc.fecha_registro DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$historial = [];
while ($fila = $result->fetch_assoc()) {
    $historial[$fila['id_solicitud']]['descripcion'] = $fila['descripcion'];
    $historial[$fila['id_solicitud']]['total'] = $fila['total'];
    $historial[$fila['id_solicitud']]['fecha_registro'] = $fila['fecha_registro'];
    $historial[$fila['id_solicitud']]['estatus'] = $fila['estatus'];
    $historial[$fila['id_solicitud']]['articulos'][] = [
        'id_articulo' => $fila['id_articulo'],
        'nombre_articulo' => $fila['nombre_articulo'],
        'cantidad' => $fila['cantidad'],
        'subtotal' => $fila['subtotal'],
        'total' => $fila['total_articulo']
    ];
}
?>

<style>
    .container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }
    .card {
        background: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 400px;
    }
    .card h2 {
        margin: 0 0 10px;
        font-size: 1.2em;
        color: #333;
    }
    .card p {
        margin: 5px 0;
        font-size: 0.9em;
        color: #666;
    }
    .items {
        margin-top: 10px;
        border-top: 1px solid #ddd;
        padding-top: 10px;
    }
    .item {
        font-size: 0.85em;
        margin: 5px 0;
    }
</style>

<body>
    <h1 class="titulo">Historial de Compras</h1>
    <div class="container">
        <?php if (empty($historial)) : ?>
            <p>No hay compras registradas.</p>
        <?php else : ?>
            <?php foreach ($historial as $id_solicitud => $compra) : ?>
                <div class="card">
                    <h2>Solicitud #<?= htmlspecialchars($id_solicitud) ?></h2>
                    <p><strong>Descripción:</strong> <?= htmlspecialchars($compra['descripcion']) ?></p>
                    <p><strong>Total:</strong> $<?= number_format($compra['total'], 2) ?></p>
                    <p><strong>Fecha:</strong> <?= htmlspecialchars($compra['fecha_registro']) ?></p>
                    <p><strong>Estatus:</strong> <?= htmlspecialchars($compra['estatus']) ?></p>
                    <div class="items">
                        <strong>Artículos:</strong>
                        <?php foreach ($compra['articulos'] as $articulo) : ?>
                            <p class="item">
                                <?= htmlspecialchars($articulo['nombre_articulo']) ?> (<?= htmlspecialchars($articulo['cantidad']) ?>) - Subtotal: $<?= number_format($articulo['subtotal'], 2) ?> - Total: $<?= number_format($articulo['total'], 2) ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>

<?php include('../../Nav/footer.php'); ?>
</html>
