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
    <style>
    .status-container {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        margin: 20px auto;
        width: fit-content;
        border-radius: 10px;
        font-weight: bold;
        font-size: 16px;
        text-align: center;
    }

    .status-pending {
        background-color: #ffcc00;
        color: #5c3b00;
        border: 1px solid #e6b800;
    }

    .status-shipped {
        background-color: #007bff;
        color: white;
        border: 1px solid #0056b3;
    }

    .status-delivered {
        background-color: rgb(85, 211, 35);
        color: white;
        border: 1px solid rgb(196, 166, 33);
    }

    .status-returned {
        background-color: #dc3545;
        color: white;
        border: 1px solid #b02a37;
    }
    
    .product-details {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .product-image-venta {
        width: 200px;
    }

    .product-info {
        max-width: 600px;
    }

    .listado-pedidos {
        margin-top: 20px;
    }

    .reporte-container {
        margin-bottom: 40px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .button-container {
        text-align: center;
        margin-top: 20px;
    }

    .button-container a {
        display: inline-block;
        padding: 10px 20px;
        margin: 10px;
        border-radius: 5px;
        font-weight: bold;
        text-decoration: none;
    }
</style>
</head>
<body>
    <h1 class="titulo">Aprobar Compras</h1>
    
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
                            <p><strong>Fecha de Registro:</strong> <?php echo htmlspecialchars($pedido['fecha_registro']); ?></p>
                            <p><strong>Producto:</strong> <?php echo htmlspecialchars($producto['nombre_producto']); ?></p>
                            <p><strong>Descripción:</strong> <?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <p><strong>Categoría:</strong> <?php echo htmlspecialchars($producto['categoria']); ?></p>
                            <p><strong>P/U:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>
                            <p><strong>Cantidad:</strong> <?php echo htmlspecialchars($producto['cantidad']); ?></p>
                            <p><strong>Personalización:</strong> <?php echo htmlspecialchars($producto['personalizacion']); ?></p>

                            <?php
                            $estatus = htmlspecialchars($pedido['estatus']);
                            $statusClass = '';

                            switch ($estatus) {
                                case 'Pendiente':
                                    $statusClass = 'status-pending';
                                    break;
                                case 'Enviado':
                                    $statusClass = 'status-shipped';
                                    break;
                                case 'Entregado':
                                    $statusClass = 'status-delivered';
                                    break;
                                case 'Devuelto':
                                    $statusClass = 'status-returned';
                                    break;
                                default:
                                    $statusClass = 'status-pending';
                            }
                            ?>
                            <div class="status-container <?php echo $statusClass; ?>">
                                <p><strong>Estatus:</strong> <?php echo $estatus; ?></p>
                            </div>
                        </div>
                    </div>

                    <?php if ($pedido['estatus'] === 'Entregado') : ?>
                        <div class="button-container">
                            <a href="../Home/encuesta.php?id_producto=<?php echo $producto['id_producto']; ?>" 
                               style="background-color: rgb(36, 176, 73); color: white;">
                               Encuesta de Satisfacción
                            </a>
                            <a href="actualizar_estatus.php?id_producto=<?php echo $producto['id_producto']; ?>&estatus=Devolución" 
                               style="background-color: rgb(234, 168, 12); color: white;">
                               Devolución
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endwhile; ?>
    </div>
</body>
<?php include('../Nav/footer.php'); ?>
</html>
