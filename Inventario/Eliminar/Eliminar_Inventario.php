<?php
include('../../BD/ConexionBD.php');

// Verificar si se ha enviado el ID del artículo a eliminar
if (isset($_GET['id_articulo'])) {
    $id_articulo = $_GET['id_articulo'];

    // Verificar si la conexión a la base de datos es exitosa
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Iniciar una transacción
    mysqli_begin_transaction($conn);

    try {
        // SQL para eliminar el artículo de la tabla Artículos
        $sql_articulo = "DELETE FROM articulos WHERE id_articulo = '$id_articulo'";
        if ($conn->query($sql_articulo) !== TRUE) {
            throw new Exception("Error al eliminar el registro en la tabla Artículos.");
        }

        // Si la consulta fue exitosa, confirmar la transacción
        mysqli_commit($conn);

        // Redirigir a la página de artículos después de eliminar el artículo
        header("Location: ../Inventario.php");
        exit;

    } catch (Exception $e) {
        // Si ocurre un error, revertir la transacción
        mysqli_rollBack($conn);
        echo "Error al eliminar el artículo: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "ID de artículo no proporcionado.";
}
?>
