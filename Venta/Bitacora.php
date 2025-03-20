<?php
include('../BD/ConexionBD.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
include('../Nav/header.php');
?>
<body>
    <h1 class="titulo">Bitácora de Pedidos</h1>

    <table class='tabla_inv'>
        <thead>
            <tr class='cont'>
                <th scope='col'>ID Pedido Bitácora</th>
                <th scope='col'>ID Pedido</th>
                <th scope='col'>ID Usuario</th>
                <th scope='col'>Estatus del Pedido</th>
                <th scope='col'>Fecha de Registro</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta SQL para obtener los registros de la bitácora de pedidos
            $sql = "SELECT * FROM pedido_bitacora";
            
            $result = $conn->query($sql);

            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                while ($fila = $result->fetch_assoc()) :
            ?>
                    <tr>
                        <th scope='row'><?= htmlspecialchars($fila['id_pedido_bitacora']) ?></th>
                        <td><?= htmlspecialchars($fila['id_pedido']) ?></td>
                        <td><?= htmlspecialchars($fila['id_usuario']) ?></td>
                        <td><?= htmlspecialchars($fila['estatus_pedido']) ?></td>
                        <td><?= htmlspecialchars($fila['fecha_registro']) ?></td>
                    </tr>
            <?php
                endwhile;
            } else {
                echo "<tr><td colspan='5'>No hay registros en la bitácora de pedidos.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
<?php
include('../Nav/footer.php');
?>
</html>
