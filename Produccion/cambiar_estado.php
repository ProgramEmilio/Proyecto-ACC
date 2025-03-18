<?php

include('../BD/ConexionBD.php'); // Conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id_producto'];
    $nuevo_estado = $_POST['estado'];

    // Verificar que se recibió un ID válido
    if (!empty($id_producto)) {
        // Asegurar que la relación es correcta
        $sql = "UPDATE pedido 
                SET estatus = ? 
                WHERE id_pedido = (SELECT id_pedido FROM producto WHERE id_producto = ? LIMIT 1)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nuevo_estado, $id_producto);

        if ($stmt->execute()) {
            echo "<script>
                alert('Estado actualizado correctamente');
                window.location.href='productor.php'; // Refrescar la página
            </script>";
        } else {
            echo "<script>alert('Error al actualizar el estado'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('ID no válido'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Acceso denegado'); window.location.href='productos.php';</script>";
}

$conn->close();
?>