<?php
include('../../BD/ConexionBD.php');

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_articulo = $_POST["id_articulo"];
    $nombre_articulo = $_POST["nombre_articulo"];
    $descripcion = $_POST["descripcion"];
    $categoria = $_POST["categoria"];
    $precio = $_POST["precio"];
    $costo = $_POST["costo"];
    $existencias = $_POST["existencias"];
    $fecha_registro = $_POST["fecha_registro"];  // Asumimos que se recibe en formato adecuado para SQL

    // Verificar si la conexión a la base de datos es exitosa
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // SQL para actualizar los datos del artículo
    $sql_articulo = "UPDATE articulos 
                     SET nombre_articulo = '$nombre_articulo', 
                         descripcion = '$descripcion', 
                         categoria = '$categoria', 
                         precio = '$precio', 
                         costo = '$costo', 
                         existencias = '$existencias', 
                         fecha_registro = '$fecha_registro' 
                     WHERE id_articulo = '$id_articulo'";

    // Ejecutar la consulta de actualización para el artículo
    if ($conn->query($sql_articulo) === TRUE) {
        // Si la actualización del artículo fue exitosa, redirigir a la página de artículos
        header("Location: ../Inventario.php");
        exit;
    } else {
        echo "Error al actualizar el artículo: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
