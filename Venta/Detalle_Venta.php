<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');

if (!isset($_SESSION['id_usuario'])) {
    die("Error: No hay un usuario en sesión.");
}

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$id_comprador_usuario = $_SESSION['id_usuario'];

$sql = "SELECT id_persona FROM persona WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_comprador_usuario);
$stmt->execute();
$stmt->bind_result($id_persona);
$stmt->fetch();
$stmt->close();

// Consulta para obtener los pedidos del usuario
$sqlPedidos = "SELECT id_pedido, estatus, fecha_registro FROM pedido WHERE id_cliente = ?";
$stmtPedidos = mysqli_prepare($conn, $sqlPedidos);
mysqli_stmt_bind_param($stmtPedidos, "i", $id_persona);
mysqli_stmt_execute($stmtPedidos);
$resultPedidos = mysqli_stmt_get_result($stmtPedidos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobar Compras</title>
    <link rel="stylesheet" href="../CSS/menu.css">
    <link rel="stylesheet" href="../CSS/cabecera.css">
    <link rel="stylesheet" href="../CSS/tablas_boton.css">
    <link rel="stylesheet" href="../CSS/Detalle_Venta.css">
</head>
<body>
    <h1 class="titulo">Aprobar Compras</h1>
    
    <?php
    /*
        if (mysqli_num_rows($result) > 0) {
            
        }else {
            echo "No hay reportes disponibles.";
        }
    */
    ?>
    <div class="listado-pedidos">
<?php while ($pedido = mysqli_fetch_assoc($resultPedidos)) : ?>
    <?php
        $sqlProductos =  "SELECT pro.id_producto, pro.id_pedido, pro.id_articulo, pro.id_cliente, pro.id_productor, 
                           pro.nombre_producto, pro.cantidad, pro.personalizacion, pro.fecha, 
                           art.descripcion, art.categoria, art.precio, art.imagen  
                            FROM producto pro
                            JOIN articulos art ON pro.id_articulo = art.id_articulo
                            WHERE pro.id_pedido = ?";
        $stmtProductos = mysqli_prepare($conn, $sqlProductos);
        mysqli_stmt_bind_param($stmtProductos, "s", $pedido['id_pedido']);
        mysqli_stmt_execute($stmtProductos);
        $resultProductos = mysqli_stmt_get_result($stmtProductos);
    ?>
    
    <?php while ($producto = mysqli_fetch_assoc($resultProductos)) : ?>
        <div class="reporte-container">
            <h2>Pedido ID: <?php echo htmlspecialchars($pedido['id_pedido']); ?></h2>

            <div class="product-details">
                <div class="product-image-venta">
                    <img src="../Imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Producto">
                </div>

                <div class="product-info">
                    <p><strong>Estatus:</strong> <?php echo htmlspecialchars($pedido['estatus']); ?></p>
                    <p><strong>Fecha de Registro:</strong> <?php echo htmlspecialchars($pedido['fecha_registro']); ?></p>
                    <p><strong>Producto:</strong> <?php echo htmlspecialchars($producto['nombre_producto']); ?></p>
                    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?></p>
                    <p><strong>Categoría:</strong> <?php echo htmlspecialchars($producto['categoria']); ?></p>
                    <p><strong>P/U:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>
                    <p><strong>Cantidad:</strong> <?php echo htmlspecialchars($producto['cantidad']); ?></p>
                    <p><strong>Personalización:</strong> <?php echo htmlspecialchars($producto['personalizacion']); ?></p>
                </div>
                
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="../Home/encuesta.php?id_producto=<?php echo $producto['id_producto']; ?>" 
                style="display: inline-block; padding: 10px 20px; margin: 10px; background-color:rgb(36, 176, 73); color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Encuesta de Satisfacción
                </a>
                
                <a href="actualizar_estatus.php?id_producto=<?php echo $producto['id_producto']; ?>&estatus=Devolución" 
                style="display: inline-block; padding: 10px 20px; margin: 10px; background-color:rgb(234, 168, 12); color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Devolución
                </a>
            </div>
        </div>
    <?php endwhile; ?>
<?php endwhile; ?>
</div>

    
</body>
<?php
include('../Nav/footer.php');
?>
</html>
