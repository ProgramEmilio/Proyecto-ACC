<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');

// Verifica si se recibe un id válido en la URL
if (isset($_GET['id_articulo']) && is_numeric($_GET['id_articulo'])) {
    $id_articulo = intval($_GET['id_articulo']); // Sanitiza el valor

    // Consulta para obtener detalles del artículo
    $sql = "SELECT id_articulo, nombre_articulo, descripcion, precio, imagen 
            FROM articulos 
            WHERE id_articulo = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_articulo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $articulo = $result->fetch_assoc();
    } else {
        echo "<p class='error'>Artículo no encontrado.</p>";
        exit();
    }

    $stmt->close();
} else {
    echo "<p class='error'>ID de artículo inválido.</p>";
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($articulo['nombre_articulo']); ?></title>
</head>
<body>

    <div class="product-details-container">
        <div class="product-card">
            
            <!-- Imagen del artículo -->
            <div class="product-image">
                <img src="../Imagenes/<?php echo htmlspecialchars($articulo['imagen'] ?: 'default.jpg'); ?>" 
                     alt="<?php echo htmlspecialchars($articulo['nombre_articulo']); ?>">
            </div>

            <!-- Información del artículo -->
            <div class="product-info">
                <h1><?php echo htmlspecialchars($articulo['nombre_articulo']); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($articulo['descripcion'])); ?></p>

                <div class="price">
                    <p><strong>Precio:</strong> $<?php echo number_format($articulo['precio'], 2); ?></p>
                </div>

                <form action="comprar.php" method="POST">
                <input type="hidden" name="id_articulo" value="<?php echo $articulo['id_articulo']; ?>">
                
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