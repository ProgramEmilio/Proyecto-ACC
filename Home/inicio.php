<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');

$sql = "SELECT id_producto, nombre_producto, descripcion, precio_unitario, impuestos, imagen FROM producto";
$result = $conn->query($sql);
?>

<body>
    <!-- =================================
       Productos (Desde la Base de Datos)
    ================================== -->
    <div class="products-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product'>";
                echo "<div class='product-img-container'>";

                // Si hay imagen, la muestra; si no, pone una imagen por defecto
                $imagen = !empty($row["imagen"]) ? $row["imagen"] : "default.jpg";
                echo "<img src='../Imagenes/" . htmlspecialchars($imagen) . "' alt='Producto'>";

                // Enlace para ver más detalles
                echo "<a href='../Producto/Detalle.php?id_producto=" . $row["id_producto"] . "' class='view-more'>Ver más</a>";
                echo "</div>";

                // Nombre del producto
                echo "<p class='product-name'>" . htmlspecialchars($row["nombre_producto"]) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No se encontraron productos.</p>";
        }
        ?>
    </div>

</body>

<?php
$conn->close(); // Cierra la conexión a la BD
include('../Nav/footer.php');
?>

</html>
