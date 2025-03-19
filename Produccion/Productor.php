<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');
// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$id_productor_usuario = $_SESSION['id_usuario'];

?>

<body>
    <h1 class="titulo">Lista de Productos</h1>

    <?php
    // Consulta SQL para obtener los productos y su información
    $sql = "SELECT p.id_producto, p.nombre_producto, p.descripcion, 
    p.categoria, p.precio_unitario, p.impuestos, p.cantidad, p.personalizacion, 
    p.fecha, pedi.id_pedido, pe.nom_persona AS cliente, 
    pe.apellido_paterno AS ap_p, pe.apellido_materno as ap_m, 
    pedi.fecha_registro AS fecha_registro, pedi.estatus
    FROM producto p
    JOIN pedido pedi ON p.id_pedido = pedi.id_pedido
    LEFT JOIN persona pe ON pe.id_persona = p.id_cliente
    WHERE pedi.estatus = 'En preparacion'";

    $result = $conn->query($sql);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        echo "<table class='tabla'>
                <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Personalización</th>
                        <th>Cliente</th>
                        <th>Estatus</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>";

        while ($fila = $result->fetch_assoc()) {
            // Asignar clase de estatus
            $estatus_class = '';
            switch ($fila['estatus']) {
                case 'Generado':
                    $estatus_class = 'estatus-generado';
                    break;
                case 'En preparacion':
                    $estatus_class = 'estatus-en-preparacion';
                    break;
                case 'A enviar':
                    $estatus_class = 'estatus-a-enviar';
                    break;
                case 'En distribucion':
                    $estatus_class = 'estatus-en-distribucion';
                    break;
                case 'En camino':
                    $estatus_class = 'estatus-en-camino';
                    break;
                case 'Entregado':
                    $estatus_class = 'estatus-entregado';
                    break;
                default:
                    $estatus_class = 'estatus-generado'; // Default
            }

            echo "<tr>
                    <td>" . $fila['id_producto'] . "</td>
                    <td>" . $fila['nombre_producto'] . "</td>
                    <td>" . $fila['descripcion'] . "</td>
                    <td>" . $fila['categoria'] . "</td>
                    <td>" . $fila['precio_unitario'] . "</td>
                    <td>" . $fila['cantidad'] . "</td>
                    <td>" . $fila['personalizacion'] . "</td>
                    <td>" . $fila['cliente'] . " " . $fila['ap_p'] . " " . $fila['ap_m'] . "</td>
                    <td><div class='estatus $estatus_class'>" . $fila['estatus'] . "</div></td>
                    <td><a href='producir_pedido.php?id_pedido=" . $fila['id_pedido'] . "'>Producir</a></td>
                </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron productos.</p>";
    }
    ?>

    <a href="pro.php" class="regresar">Regresar</a>

</body>

<?php
include('../Nav/footer.php');
?>
</html>
