<?php
include('../BD/ConexionBD.php');

// Verificar la conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

if (isset($_GET['id_producto']) && isset($_GET['estatus']) && isset($_GET['id_usuario'])) {
    $id_producto = $_GET['id_producto'];
    $estatus = $_GET['estatus'];
    $id_usuario = $_GET['id_usuario']; // El distribuidor que cambia el estado

    // Obtener el id_pedido correspondiente al producto
    $sql_pedido = "SELECT id_pedido FROM producto WHERE id_producto = '$id_producto'";
    $result_pedido = $conn->query($sql_pedido);

    if ($result_pedido->num_rows > 0) {
        $pedido = $result_pedido->fetch_assoc();
        $id_pedido = $pedido['id_pedido'];

        // Insertar un nuevo registro en la tabla pedido_bitacora
        $fecha_registro = date('Y-m-d H:i:s');
        $sql_bitacora = "INSERT INTO pedido_bitacora (id_pedido, id_usuario, estatus_pedido, fecha_registro)
                         VALUES ('$id_pedido', '$id_usuario', '$estatus', '$fecha_registro')";

        if ($conn->query($sql_bitacora) === TRUE) {
            // Redirigir a la página para actualizar el estado
            header("Location: actualizar_estatus.php?id_producto=$id_producto&estatus=$estatus");
            exit();
        } else {
            echo "Error al registrar el cambio en la bitácora: " . $conn->error;
        }
    } else {
        echo "Pedido no encontrado.";
    }
} else {
    echo "Faltan parámetros.";
}
?>