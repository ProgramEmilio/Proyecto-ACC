<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');

$sql = "SELECT id_articulo, nombre_articulo, imagen 
        FROM articulos 
        WHERE categoria = 'Producto'";
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
                echo "<div class='product-card'>"; // Usando la clase de tarjeta
                echo "<div class='product-image'>";

                // Si hay imagen, la muestra; si no, pone una imagen por defecto
                $imagen = !empty($row["imagen"]) ? $row["imagen"] : "default.jpg";
                echo "<img src='../Imagenes/" . htmlspecialchars($imagen) . "' alt='Producto'>";

                echo "</div>"; // Cierre de 'product-image'

                // Información del producto
                echo "<div class='product-info'>";
                echo "<h1 class='product-name'>" . htmlspecialchars($row["nombre_articulo"]) . "</h1>";

                // Enlace para ver más detalles
                echo "<a href='../Producto/Detalle.php?id_articulo=" . $row["id_articulo"] . "' class='view-more'>Ver más</a>";

                echo "</div>"; // Cierre de 'product-info'
                echo "</div>"; // Cierre de 'product-card'
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
