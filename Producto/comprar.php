<?php
include('../BD/ConexionBD.php');
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    echo "<p class='error'>Debes estar logueado para realizar una compra.</p>";
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener los datos del formulario
$id_articulo = isset($_POST['id_articulo']) ? intval($_POST['id_articulo']) : 0;
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
$personalizacion = isset($_POST['personalizacion']) ? $_POST['personalizacion'] : null;

// Validar los datos
if ($id_articulo <= 0 || $cantidad <= 0 || !$personalizacion) {
    echo "<p class='error'>Datos inválidos.</p>";
    exit();
}

// Generar un ID de pedido con el prefijo "PED-"
$id_pedido = 'PED-' . strtoupper(uniqid());

// Obtener detalles del artículo
$sql_articulo = "SELECT a.nombre_articulo, a.precio FROM articulos a WHERE a.id_articulo = ?";
$stmt = $conn->prepare($sql_articulo);
$stmt->bind_param("i", $id_articulo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $articulo = $result->fetch_assoc();
} else {
    echo "<p class='error'>Artículo no encontrado.</p>";
    exit();
}

$stmt->close();

// Fecha actual
$fecha_registro = date('Y-m-d H:i:s');

// Insertar el pedido en la tabla 'pedido'
$sql_insert_pedido = "INSERT INTO pedido (id_pedido, id_cliente, estatus, fecha_registro) VALUES (?, ?, 'Generado', ?)";
$stmt = $conn->prepare($sql_insert_pedido);
$stmt->bind_param("sis", $id_pedido, $id_usuario, $fecha_registro);
$stmt->execute();
$stmt->close();

// Insertar el producto asociado al pedido en la tabla 'producto'
$sql_insert_producto = "INSERT INTO producto (id_pedido, id_articulo, id_cliente, nombre_producto, cantidad, personalizacion, fecha) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert_producto);
$stmt->bind_param("siisiss", $id_pedido, $id_articulo, $id_usuario, $articulo['nombre_articulo'], $cantidad, $personalizacion, $fecha_registro);
$stmt->execute();
$stmt->close();

// Generar un ID aleatorio para id_pedido_bitacora
$id_pedido_bitacora = rand(100000, 999999); // Asegúrate de que este rango no cause conflictos con los IDs existentes

// Insertar el producto asociado al pedido en la tabla 'pedido_bitacora'
$sql_insert_pedido_bitacora = "INSERT INTO pedido_bitacora (id_pedido_bitacora, id_pedido, id_usuario, estatus_pedido, fecha_registro) 
                               VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql_insert_pedido_bitacora); // Usamos el nombre correcto de la variable SQL
$stmt->bind_param("issss", $id_pedido_bitacora, $id_pedido, $id_usuario, $estatus_pedido, $fecha_registro); // Aseguramos que los tipos coincidan
$estatus_pedido = 'Generado'; // Asignar valor 'Generado' al estatus
$stmt->execute();
$stmt->close();

// Cerrar la conexión
$conn->close();

// Redirigir al usuario con un mensaje de éxito
echo "<script>alert('Compra realizada con éxito.'); window.location.href='../Home/inicio.php';</script>";
?>
