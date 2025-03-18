<?php
include('../BD/ConexionBD.php');
session_start();

if (!isset($_SESSION['id_cliente'])) {
    echo "<p class='error'>Debes iniciar sesión para comprar.</p>";
    exit();
}

$id_cliente = $_SESSION['id_cliente'];
$id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
$personalizacion = isset($_POST['personalizacion']) ? $_POST['personalizacion'] : null;

// Validaciones básicas
if ($id_producto <= 0 || $cantidad <= 0) {
    echo "<p class='error'>Datos inválidos.</p>";
    exit();
}

// Obtener los datos del producto
$sql_producto = "SELECT nombre_producto, descripcion, precio_unitario, impuestos, imagen FROM producto WHERE id_producto = ?";
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

// Buscar un pedido activo del cliente
$sql_pedido = "SELECT id_pedido FROM pedido WHERE id_cliente = ? AND estatus = 'Generado'";
$stmt = $conn->prepare($sql_pedido);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $pedido = $result->fetch_assoc();
    $id_pedido = $pedido['id_pedido'];
} else {
    // Si no hay un pedido activo, crear uno nuevo
    $id_pedido = uniqid('PED'); // Generar un ID único
    $fecha_registro = date('Y-m-d H:i:s');

    $sql_insert_pedido = "INSERT INTO pedido (id_pedido, id_cliente, estatus, fecha_registro) VALUES (?, ?, 'Generado', ?)";
    $stmt = $conn->prepare($sql_insert_pedido);
    $stmt->bind_param("sis", $id_pedido, $id_cliente, $fecha_registro);
    $stmt->execute();
    $stmt->close();
}

// Insertar el producto en la tabla producto
$fecha = date('Y-m-d H:i:s');
$sql_insert_producto = "INSERT INTO producto (id_pedido, nombre_producto, descripcion, precio_unitario, impuestos, cantidad, personalizacion, id_cliente, fecha, imagen) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql_insert_producto);
$stmt->bind_param("sssddiisss", $id_pedido, $producto['nombre_producto'], $producto['descripcion'], $producto['precio_unitario'], $producto['impuestos'], $cantidad, $personalizacion, $id_cliente, $fecha, $producto['imagen']);
$stmt->execute();
$stmt->close();

$conn->close();

echo "<p class='success'>¡Compra realizada con éxito!</p>";
echo "<a href='../Home/inicio.php'>Volver a inicio</a>";
?>
