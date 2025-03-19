<?php
include('../BD/ConexionBD.php');
session_start();

$id_usuario = $_SESSION['id_usuario'];
$id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;  // Agregar el signo '$'
$personalizacion = isset($_POST['personalizacion']) ? $_POST['personalizacion'] : null;

// Validaciones
if ($id_producto <= 0 || $cantidad <= 0) {
    echo "<p class='error'>Datos inválidos.</p>";
    exit();
}

// Generar nuevo pedido
$id_pedido = uniqid('PED');
$fecha_registro = date('Y-m-d H:i:s');

$sql_insert_pedido = "INSERT INTO pedido (id_pedido, id_cliente, estatus, fecha_registro) VALUES (?, ?, 'Generado', ?)";
$stmt = $conn->prepare($sql_insert_pedido);
$stmt->bind_param("sis", $id_pedido, $id_usuario, $fecha_registro);
$stmt->execute();
$stmt->close();

// Insertar producto asociado al pedido
$fecha = date('Y-m-d H:i:s');
$sql_insert_producto = "INSERT INTO producto (id_pedido, id_articulo, id_cliente, nombre_producto, cantidad, personalizacion, fecha) VALUES (?, ?, ?, (SELECT nombre FROM articulos WHERE id_articulo = ?), ?, ?, ?)";

$stmt = $conn->prepare($sql_insert_producto);
$stmt->bind_param("siisiss", $id_pedido, $id_producto, $id_usuario, $id_producto, $cantidad, $personalizacion, $fecha);
$stmt->execute();
$stmt->close();

$conn->close();

echo "<script>alert('Solicitud enviada correctamente.'); window.location.href='../Home/inicio.php';</script>";
echo "<p class='success'>¡Compra realizada con éxito!</p>";
echo "<a href='../Home/inicio.php'>Volver a inicio</a>";
?>
