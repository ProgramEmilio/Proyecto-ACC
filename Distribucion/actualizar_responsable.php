<?php
include('../BD/ConexionBD.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = $_POST['id_producto'];
    $distribuidor_id = $_POST['distribuidor'];

    // Actualizar distribuidor en la base de datos
    $sql = "UPDATE pedido SET id_distribuidor = ? WHERE id_pedido = (SELECT id_pedido FROM producto WHERE id_producto = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $distribuidor_id, $id_producto);

    if ($stmt->execute()) {
        echo "Distribuidor actualizado correctamente.";
        // Redirigir a la página de detalle del pedido
        header("Location: detalle.php?id_producto=$id_producto");
        exit();
    } else {
        echo "Error al actualizar distribuidor: " . $conn->error;
    }
}
?>
