<?php
include('../../BD/ConexionBD.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    die("Error: No hay un usuario en sesión.");
}

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$id_comprador_usuario = $_SESSION['id_usuario'];

// Obtener los detalles de la solicitud de compra con estatus 'cotizada'
$sql = "SELECT scd.id_solicitud_detalle, scd.id_solicitud, scd.id_articulo, a.nombre_articulo, scd.cantidad, scd.total
        FROM solicitud_compra_detalle scd
        JOIN articulos a ON scd.id_articulo = a.id_articulo
        JOIN solicitud_compra sc ON scd.id_solicitud = sc.id_solicitud
        WHERE sc.estatus = 'cotizada'";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aprobar'])) {
    $id_solicitud = $_POST['id_solicitud'];
    $id_articulo = $_POST['id_articulo'];
    $cantidad = $_POST['cantidad'];
    
    // Actualizar existencias en la tabla articulos
    $update_existencias_sql = "UPDATE articulos 
                                SET existencias = existencias + ? 
                                WHERE id_articulo = ?";
    $stmt = $conn->prepare($update_existencias_sql);
    $stmt->bind_param("ii", $cantidad, $id_articulo);
    $stmt->execute();
    $stmt->close();
    
    // Actualizar estatus de la solicitud_compra a 'comprado'
    $update_solicitud_sql = "UPDATE solicitud_compra 
                              SET estatus = 'comprado' 
                              WHERE id_solicitud = ?";
    $stmt = $conn->prepare($update_solicitud_sql);
    $stmt->bind_param("i", $id_solicitud);
    $stmt->execute();
    $stmt->close();
    
    echo "<script>alert('Compra aprobada correctamente.'); window.location.href='Aprobar_Compra.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobar Compras</title>
    <link rel="stylesheet" href="../../CSS/menu.css">
    <link rel="stylesheet" href="../../CSS/cabecera.css">
    <link rel="stylesheet" href="../../CSS/tablas_boton.css">
</head>
<body>
    <h1 class="titulo">Aprobar Compras</h1>

    <table class='tabla'>
        <thead>
            <tr>
                <th>ID Solicitud</th>
                <th>ID Artículo</th>
                <th>Nombre Artículo</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Aprobar</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= htmlspecialchars($fila['id_solicitud']) ?></td>
                <td><?= htmlspecialchars($fila['id_articulo']) ?></td>
                <td><?= htmlspecialchars($fila['nombre_articulo']) ?></td>
                <td><?= htmlspecialchars($fila['cantidad']) ?></td>
                <td><?= htmlspecialchars($fila['total']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_solicitud" value="<?= $fila['id_solicitud'] ?>">
                        <input type="hidden" name="id_articulo" value="<?= $fila['id_articulo'] ?>">
                        <input type="hidden" name="cantidad" value="<?= $fila['cantidad'] ?>">
                        <button class='regresar' type="submit" name="aprobar">Aprobar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
