<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');

$sql = "SELECT id_articulo, nombre_articulo, imagen 
        FROM articulos 
        WHERE categoria = 'Producto'";
$result = $conn->query($sql);
?>

<link rel="stylesheet" href="productos.css">

<body>
    <!-- =================================
       Productos (Desde la Base de Datos)
    ================================== -->

    <div class="products-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product-card'>";
                
                // Nombre del producto
                echo "<h1 class='product-name'>" . htmlspecialchars($row["nombre_articulo"]) . "</h1>";

                // Imagen del producto
                echo "<div class='product-image'>";
                $imagen = !empty($row["imagen"]) ? $row["imagen"] : "default.jpg";
                echo "<img src='../Imagenes/" . htmlspecialchars($imagen) . "' alt='Producto'>";
                echo "</div>";

                // Botón Ver más
                echo "<a href='../Producto/Detalle.php?id_articulo=" . $row["id_articulo"] . "' class='view-more'>Ver más</a>";

                echo "</div>";
            }
        } else {
            echo "<p>No se encontraron productos.</p>";
        }
        ?>
    </div>

</body>

<?php
$conn->close();
include('../Nav/footer.php');
?>
</html>
