<?php
include('../BD/ConexionBD.php');

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener parámetros de la URL
$id_producto = $_GET['id_producto'];
$estatus = $_GET['estatus'];

// Actualizar el estatus en la tabla pedido
$sql = "UPDATE pedido SET estatus = ? WHERE id_pedido = (SELECT id_pedido FROM producto WHERE id_producto = ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $estatus, $id_producto);

if ($stmt->execute()) {
    header("Location: detalle.php?id_producto=$id_producto");
} else {
    echo "<p>Error al actualizar el estatus: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();
?>
