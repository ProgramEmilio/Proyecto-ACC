<?php
include('../BD/ConexionBD.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar si se ha recibido el id_producto
if (isset($_GET['id_producto'])) {
    $id_producto = $_GET['id_producto'];

    // Obtener el id_pedido del producto seleccionado
    $query_pedido = "SELECT id_pedido FROM producto WHERE id_producto = ?";
    if ($stmt = $conn->prepare($query_pedido)) {
        $stmt->bind_param('i', $id_producto);
        $stmt->execute();
        $stmt->bind_result($id_pedido);
        $stmt->fetch();
        $stmt->close();

        if (!empty($id_pedido)) {
            // Actualizar el estatus del pedido a "A enviar"
            $sql_update = "UPDATE pedido SET estatus = 'A enviar' WHERE id_pedido = ?";
            if ($stmt_update = $conn->prepare($sql_update)) {
                $stmt_update->bind_param('s', $id_pedido);
                if ($stmt_update->execute()) {
                    header("Location: productor.php");
                    exit();
                } else {
                    echo "Error al cambiar el estatus: " . $conn->error;
                }
                $stmt_update->close();
            }
        } else {
            echo "No se encontró un pedido asociado a este producto.";
        }
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }
} else {
    echo "ID de producto no recibido.";
}
?>
