<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');

$sql = "SELECT id_articulo, nombre_articulo, imagen 
        FROM articulos 
        WHERE categoria = 'Producto'";
$result = $conn->query($sql);
?>

<body>
    <link rel="stylesheet" href="productos.css">

    <div class="products-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product-card'>"; // Tarjeta completa

                // Nombre del producto ARRIBA
                echo "<h1 class='product-name'>" . htmlspecialchars($row["nombre_articulo"]) . "</h1>";

                // Imagen en el centro
                echo "<div class='product-image'>";
                $imagen = !empty($row["imagen"]) ? $row["imagen"] : "default.jpg";
                echo "<img src='../Imagenes/" . htmlspecialchars($imagen) . "' alt='Producto'>";
                echo "</div>";

                // Botón ABAJO
                echo "<div class='product-info'>";
                echo "<a href='../Producto/Detalle.php?id_articulo=" . $row["id_articulo"] . "' class='view-more'>Ver más</a>";
                echo "</div>";

                echo "</div>"; // Cierre product-card
            }
        } else {
            echo "<p>No se encontraron productos.</p>";
        }
        ?>
    </div>

</body>

<?php
$conn->close(); // Cierra conexión
include('../Nav/footer.php');
?>

</html>
