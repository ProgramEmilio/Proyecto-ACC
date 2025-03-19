<?php
include('../BD/ConexionBD.php');
session_start();

$id_usuario = $_SESSION['id_usuario'];
$id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
$personalizacion = isset($_POST['personalizacion']) ? $_POST['personalizacion'] : null;

// Validaciones
if ($id_producto <= 0 || $cantidad <= 0) {
    echo "<p class='error'>Datos inválidos.</p>";
    exit();
}

// Obtener datos del producto
$sql_producto = "SELECT descripcion, categoria, precio_unitario, impuestos FROM producto WHERE id_producto = ?";
$stmt = $conn->prepare($sql_producto);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p class='error'>Producto no encontrado.</p>";
    exit();
}

$producto = $result->fetch_assoc();
$stmt->close();

// Buscar un pedido activo
$sql_pedido = "SELECT id_pedido FROM pedido WHERE id_cliente = ? AND estatus = 'Generado'";
$stmt = $conn->prepare($sql_pedido);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();


// Crear nuevo pedido
$id_pedido = uniqid('PED');
$fecha_registro = date('Y-m-d H:i:s');

$sql_insert_pedido = "INSERT INTO pedido (id_pedido, id_cliente, estatus, fecha_registro) VALUES (?, ?, 'Generado', ?)";
$stmt = $conn->prepare($sql_insert_pedido);
$stmt->bind_param("sis", $id_pedido, $id_usuario, $fecha_registro);
$stmt->execute();
$stmt->close();

// Actualizar el producto con el id_pedido
$fecha = date('Y-m-d H:i:s');
$sql_update_producto = "UPDATE producto SET id_cliente = ?, id_pedido = ?, fecha = ? WHERE id_producto = ?";

$stmt = $conn->prepare($sql_update_producto);
$stmt->bind_param("issi", $id_usuario, $id_pedido, $fecha, $id_producto);
$stmt->execute();

if ($stmt->affected_rows == 0) {
    echo "<p class='error'>Error al actualizar el producto.</p>";
    exit();
}


$stmt->close();

$conn->close();

echo "<script>alert('Solicitud enviada correctamente.'); window.location.href='../Home/inicio.php';</script>";
echo "<p class='success'>¡Compra realizada con éxito!</p>";
echo "<a href='../Home/inicio.php'>Volver a inicio</a>";
?>
