<?php
include('../BD/ConexionBD.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar si se ha recibido el id_pedido
if (isset($_GET['id_pedido'])) {
    $id_pedido = $_GET['id_pedido'];

    // Consulta para actualizar el estatus del pedido a "A enviar"
    $sql = "UPDATE pedido SET estatus = 'A enviar' WHERE id_pedido = ?";

    // Preparar y ejecutar la consulta
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id_pedido);
        if ($stmt->execute()) {
            // Si la actualización fue exitosa, redirigir al listado de productos
            header("Location: productor.php");
            exit();
        } else {
            echo "Error al cambiar el estatus: " . $conn->error;
        }
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }
} else {
    echo "ID de pedido no recibido.";
}
?>
