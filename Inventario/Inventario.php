<?php
include('../BD/ConexionBD.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
include('../Nav/header.php');
?>
<body>
    <h1 class="titulo">Inventario</h1>

    <table class='tabla_inv'>
        <thead>
            <tr class='cont'>
                <th scope='col'>ID Artículo</th>
                <th scope='col'>Artículo</th>
                <th scope='col'>Descripción</th>
                <th scope='col'>Categoría</th>
                <th scope='col'>Existencias</th>
                <th scope='col'>Fecha Entrada</th>
                <th scope='col'>Editar</th>
                <th scope='col'>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Consulta SQL para obtener los artículos y su información
            $sql = "SELECT * FROM articulos WHERE categoria = 'Insumo'";
            
            $result = $conn->query($sql);

            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                while ($fila = $result->fetch_assoc()) :
            ?>
                    <tr>
                        <th scope='row'><?= htmlspecialchars($fila['id_articulo']) ?></th>
                        <td><?= htmlspecialchars($fila['nombre_articulo']) ?></td>
                        <td><?= htmlspecialchars($fila['descripcion']) ?></td>
                        <td><?= htmlspecialchars($fila['categoria']) ?></td>
                        <td><?= htmlspecialchars($fila['existencias']) ?></td>
                        <td><?= htmlspecialchars($fila['fecha_registro']) ?></td>
                        <td><a href='Editar/Modificar_I.php?id_articulo=<?= htmlspecialchars($fila['id_articulo']) ?>' class='editar'>Editar</a></td>
                        <td><a href='Eliminar/Eliminar_Inventario.php?id_articulo=<?= htmlspecialchars($fila['id_articulo']) ?>'>Eliminar</a></td>
                    </tr>
            <?php
                endwhile;
            } else {
                echo "<tr><td colspan='9'>No hay artículos en el inventario.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
<?php
include('../Nav/footer.php');
?>

</html>

