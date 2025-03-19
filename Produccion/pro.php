<?php
include('../BD/ConexionBD.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
include('../Nav/header.php');

?>


<body>
    <h1 class="titulo">Producción</h1>

    <?php
    // Nueva consulta SQL para obtener productos, pedidos y artículos
    $sql = "SELECT pro.id_producto, art.descripcion, art.categoria, art.precio, 
                   pro.cantidad, pro.fecha, pro.personalizacion, pro.nombre_producto,
                   pedi.id_pedido, pedi.estatus, pedi.fecha_registro,
                   p.nom_persona AS nombre_cliente, p.apellido_paterno AS ap_p, p.apellido_materno AS ap_m
            FROM pedido pedi
            JOIN producto pro ON pedi.id_pedido = pro.id_pedido
            JOIN articulos art ON pro.id_articulo = art.id_articulo
            LEFT JOIN persona p ON pedi.id_cliente = p.id_persona
            WHERE pedi.estatus = 'En preparacion' OR pedi.estatus = 'Generado'";

    $result = $conn->query($sql);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        echo "<table class='tabla'>
                <thead>
                    <tr>
                        <th>ID Pedido</th>
                        <th>Producto</th>
                        <th>Descripción Producto</th>
                        <th>Categoría</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Fecha Registro</th>
                        <th>Cliente</th>
                        <th>Personalización</th>
                        <th>Estatus Pedido</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>";

        while ($fila = $result->fetch_assoc()) {
            // Asignar clase de estatus
            $estatus_class = 'estatus-generado'; // Default
            switch ($fila['estatus']) {
                case 'Generado': $estatus_class = 'estatus-generado'; break;
                case 'En preparacion': $estatus_class = 'estatus-en-preparacion'; break;
                case 'A enviar': $estatus_class = 'estatus-a-enviar'; break;
                case 'En distribucion': $estatus_class = 'estatus-en-distribucion'; break;
                case 'En camino': $estatus_class = 'estatus-en-camino'; break;
                case 'Entregado': $estatus_class = 'estatus-entregado'; break;
            }

            echo "<tr>
                    <td>{$fila['id_pedido']}</td>
                    <td>{$fila['nombre_producto']}</td>
                    <td>{$fila['descripcion']}</td>
                    <td>{$fila['categoria']}</td>
                    <td>{$fila['precio']}</td>
                    <td>{$fila['cantidad']}</td>
                    <td>{$fila['fecha']}</td>
                    <td>{$fila['nombre_cliente']} {$fila['ap_p']} {$fila['ap_m']}</td>
                    <td>{$fila['personalizacion']}</td>
                    <td><div class='estatus $estatus_class'>{$fila['estatus']}</div></td>
                    <td><a href='detalle_pedido.php?id_pedido={$fila['id_pedido']}&id_producto={$fila['id_producto']}'>Configurar</a></td>
                </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron productos.</p>";
    }
?>


</body>

<?php
include('../Nav/footer.php');
?>

</html>
