<?php
include('../BD/ConexionBD.php');
include('../Nav/header.php');
$sql = "SELECT id_producto, nombre_producto, descripcion, precio_unitario, impuestos, imagen FROM producto";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='container-card'>"; // Contenedor de tarjetas
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card'>"; // Tarjeta individual
        if (!empty($row["imagen"])) {
            echo "<img src='../Imagenes/" . $row["imagen"] . "' alt='Imagen del producto' style='width:300px; height:370px;'/>";
        } else {
            echo "<img src='../Imagenes/' alt='Imagen no disponible' style='width:300px; height:370px;'/>";
        }
        echo "<div class='contenido-card'>";
        echo "<h3>" . htmlspecialchars($row["nombre_producto"]) . "</h3>";
        echo "<a href='Detalle.php?id_producto=" . $row["id_producto"] . "' class='mas_info'>Más Información</a>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>"; // Cierre del contenedor
} else {
    echo "No se encontraron productos.";
}

$conn->close(); // Cierra la conexión a la base de datos

include('../Nav/footer.php');

?>