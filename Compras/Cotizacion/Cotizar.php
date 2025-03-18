<?php
include('../../BD/ConexionBD.php');

include('../../Nav/header2.php');
if (!isset($_SESSION['id_usuario'])) {
    die("Error: No hay un usuario en sesión.");
}

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$id_proveedor_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario en sesión

$sql = "SELECT id_persona FROM persona WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_proveedor_usuario);
$stmt->execute();
$stmt->bind_result($id_persona);
$stmt->fetch();
$stmt->close();

// Obtener los detalles de la solicitud de compra
$sql = "SELECT scd.id_solicitud_detalle, scd.id_solicitud, scd.id_articulo, a.nombre_articulo, scd.cantidad, scd.subtotal
        FROM solicitud_compra_detalle scd
        JOIN articulos a ON scd.id_articulo = a.id_articulo
        JOIN solicitud_compra sc ON scd.id_solicitud = sc.id_solicitud
        WHERE sc.estatus = 'generada'";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cotizar'])) {
    $id_solicitud_detalle = $_POST['id_solicitud_detalle'];
    $subtotal = $_POST['subtotal'];
    $id_solicitud = $_POST['id_solicitud'];
    $cantidad = $_POST['cantidad'];
    
    $total = $cantidad * $subtotal;
    $fecha_actual = date('Y-m-d H:i:s');
    
    // Actualizar solicitud_compra_detalle
    $update_detalle_sql = "UPDATE solicitud_compra_detalle 
                           SET subtotal = ?, total = ?, fecha_registro = ? 
                           WHERE id_solicitud_detalle = ?";
    $stmt = $conn->prepare($update_detalle_sql);
    $stmt->bind_param("ddsi", $subtotal, $total, $fecha_actual, $id_solicitud_detalle);
    $stmt->execute();
    $stmt->close();
    
    // Calcular sumatoria de totales por id_solicitud
    $sum_sql = "SELECT SUM(total) AS total_solicitud FROM solicitud_compra_detalle WHERE id_solicitud = ?";
    $stmt = $conn->prepare($sum_sql);
    $stmt->bind_param("i", $id_solicitud);
    $stmt->execute();
    $stmt->bind_result($total_solicitud);
    $stmt->fetch();
    $stmt->close();
    
    // Actualizar solicitud_compra
    if ($id_persona !== null) {
        $update_solicitud_sql = "UPDATE solicitud_compra 
                                 SET id_proveedor_usuario = ?, total = ?, estatus = 'cotizada' 
                                 WHERE id_solicitud = ?";
        $stmt = $conn->prepare($update_solicitud_sql);
        $stmt->bind_param("idi", $id_persona, $total_solicitud, $id_solicitud);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "<script>alert('Error: No se encontró el proveedor asociado al usuario.',$id_proveedor_usuario);</script>";
    }
    

    echo "<script>alert('Cotización realizada correctamente.'); window.location.href='Cotizar.php';</script>";
}
?>
<body>
    <h1 class="titulo">Cotizar Solicitud</h1>

    <table class='tabla_com'>
        <thead>
            <tr>
                <th>ID Solicitud</th>
                <th>ID Artículo</th>
                <th>Nombre Artículo</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Cotizar</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($fila = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= htmlspecialchars($fila['id_solicitud']) ?></td>
                <td><?= htmlspecialchars($fila['id_articulo']) ?></td>
                <td><?= htmlspecialchars($fila['nombre_articulo']) ?></td>
                <td><?= htmlspecialchars($fila['cantidad']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_solicitud_detalle" value="<?= $fila['id_solicitud_detalle'] ?>">
                        <input type="hidden" name="id_solicitud" value="<?= $fila['id_solicitud'] ?>">
                        <input type="hidden" name="cantidad" value="<?= $fila['cantidad'] ?>">
                        <input class='contenido' type="number" name="subtotal" step="0.01" required>
                </td>
                <td>
                        <button class='regresar' type="submit" name="cotizar">Cotizar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
<?php
include('../../Nav/footer.php');
?>
</html>
