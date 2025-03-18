<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');

// Verifica si se recibe un id válido en la URL
if (isset($_GET['id_producto']) && is_numeric($_GET['id_producto'])) {
    $id_producto = intval($_GET['id_producto']); // Sanitiza el valor

    // Consulta para obtener detalles del producto
    $sql = "SELECT id_producto, nombre_producto, descripcion, precio_unitario, impuestos, personalizacion, imagen 
            FROM producto 
            WHERE id_producto = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
    } else {
        echo "<p class='error'>Producto no encontrado.</p>";
        exit();
    }

    $stmt->close();
} else {
    echo "<p class='error'>ID de producto inválido.</p>";
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre_producto']); ?></title>
    <link rel="stylesheet" href="../CSS/Detalle_producto.css"> <!-- Agrega tu CSS aquí -->
</head>
<body>

    <div class="product-details-container">
        <div class="product-card">
            
            <!-- Imagen del producto -->
            <div class="product-image">
                <img src="../Imagenes/<?php echo htmlspecialchars($producto['imagen'] ?: 'default.jpg'); ?>" 
                     alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>">
            </div>

            <!-- Información del producto -->
            <div class="product-info">
                <h1><?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>

                <div class="price">
                    <p><strong>Precio:</strong> $<?php echo number_format($producto['precio_unitario'], 2); ?></p>
                    <p><strong>Impuestos:</strong> $<?php echo number_format($producto['impuestos'], 2); ?></p>
                </div>

                <form action="comprar.php" method="POST">
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

                <?php if (!empty($producto['personalizacion'])): ?>
                    <label for="personalizacion">Personalización:</label>
                    <input type="text" name="personalizacion" id="personalizacion" class="custom-select" placeholder="Escribe tu personalización">
                <?php endif; ?>

                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" id="cantidad" class="custom-select" placeholder="Cantidad" required min="1">

                <button type="submit" class="buy-button">Comprar</button>
            </form>
            </div>
            
        </div>
    </div>
    <a href="../Home/inicio.php" class="regresar">Regresar</a>


</body>
<?php include('../Nav/footer.php'); ?>
</html>
