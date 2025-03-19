<?php
include('../BD/ConexionBD.php');

include('../Nav/header.php');
if (isset($_POST['cerrar_sesion'])) {
    session_destroy(); // Destruye la sesión
    header("Location: ../Login/login.php"); // Redirige a la página de inicio de sesión
    exit();
}
// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
$id_distribuidor_usuario = $_SESSION['id_usuario'];
$rol_usuario = $_SESSION['id_rol'];
?>


<body>
    <h1 class="titulo">Distribución</h1>

    <?php
    // Consulta SQL para obtener los productos y su información
    $sql = "SELECT pro.id_producto, pro.nombre_producto, pro.id_pedido, pro.id_articulo, pro.id_cliente, pro.id_productor,
    pro.cantidad, pro.personalizacion, pro.fecha,
         pedi.id_pedido, pedi.id_pedido, pedi.id_cliente AS cliente_pedido, pedi.estatus, pedi.fecha_registro, pedi.id_distribuidor,
         p.nom_persona AS nombre_cliente, 
         p.apellido_paterno AS ap_p, p.apellido_materno AS ap_m,
         pedi.fecha_registro AS fecha_registro, pedi.estatus
         FROM producto pro
         JOIN pedido pedi ON pedi.id_pedido = pro.id_pedido
         LEFT JOIN persona p ON p.id_persona = pro.id_cliente
         WHERE pedi.estatus IN ('A enviar', 'En distribucion', 'En camino', 'Entregado')";

    $result = $conn->query($sql);

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        echo "<table class='tabla'>
                <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>ID Pedido</th>
                        <th>Producto</th>
                        <th>Impuestos</th>
                        <th>Fecha</th>
                        <th>Estatus</th>
                        <th>Detalle</th>
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
                    <td>" . $fila['id_pedido'] . "</td>
                    <td>" . $fila['nombre_producto'] . "</td>
                    <td>" . $fila['cantidad'] . "</td>
                    <td>" . $fila['fecha'] . "</td>
                    <td><div class='estatus $estatus_class'>" . $fila['estatus'] . "</div></td>
                    <td><a href='detalle.php?id_producto=" . $fila['id_producto'] . "'>Ver Detalle</a></td>
                </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No se encontraron productos para envío.</p>";
    }
    ?>

</body>

<?php
include('../Nav/footer.php');
?>
</html>
