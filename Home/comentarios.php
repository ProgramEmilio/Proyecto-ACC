<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta SQL para obtener la información de los comentarios
$sql = "SELECT 
        comentarios_cliente.id_comentario,
        comentarios_cliente.id_producto,
        producto.nombre_producto AS Nombre_producto,
        comentarios_cliente.calificacion,
        comentarios_cliente.comentario
    FROM comentarios_cliente
    JOIN producto ON comentarios_cliente.id_producto = producto.id_producto";

$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<body>
    <h1 class="titulo">Comentarios de Clientes</h1>
    <br>
    <table class='tabla_com'>
        <thead>
            <tr class='cont'>
                <th scope='col'>ID Comentario</th>
                <th scope='col'>ID Producto</th>
                <th scope='col'>Nombre del Producto</th>
                <th scope='col'>Calificación</th>
                <th scope='col'>Comentario</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($fila = $result->fetch_assoc()) : ?>
            <tr>
                <th scope='row'><?= htmlspecialchars($fila['id_comentario']) ?></th>
                <td><?= htmlspecialchars($fila['id_producto']) ?></td>
                <td><?= htmlspecialchars($fila['Nombre_producto']) ?></td>
                <td><?= htmlspecialchars($fila['calificacion']) ?></td>
                <td><?= htmlspecialchars($fila['comentario']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
<?php
include('../Nav/footer.php');
?>
</html>
