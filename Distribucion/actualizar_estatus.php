<?php
include('../BD/ConexionBD.php');

session_start();

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    echo "<p class='error'>Debes estar logueado para realizar una compra.</p>";
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener parámetros de la URL
$id_producto = $_GET['id_producto'];
$estatus = $_GET['estatus'];

// Actualizar el estatus en la tabla pedido
$sql = "UPDATE pedido SET estatus = ? WHERE id_pedido = (SELECT id_pedido FROM producto WHERE id_producto = ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $estatus, $id_producto);

if ($stmt->execute()) {
    
    // Generar un ID aleatorio para id_pedido_bitacora
    $id_pedido_bitacora = rand(100000, 999999);
    
    // Configurar la zona horaria y obtener la fecha actual
    date_default_timezone_set('America/Mazatlan');
    $fecha_registro = date('Y-m-d H:i:s');
    
    // Obtener id_pedido
    $sql_id_pedido = "SELECT id_pedido FROM producto WHERE id_producto = ?";
    $stmt_id_pedido = $conn->prepare($sql_id_pedido);
    $stmt_id_pedido->bind_param("i", $id_producto);
    $stmt_id_pedido->execute();
    $stmt_id_pedido->bind_result($id_pedido);
    $stmt_id_pedido->fetch();
    $stmt_id_pedido->close();
    
    // Insertar en la tabla pedido_bitacora
    $sql_insert_pedido_bitacora = "INSERT INTO pedido_bitacora (id_pedido_bitacora, id_pedido, id_usuario, estatus_pedido, fecha_registro) 
                                   VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert_pedido_bitacora);
    $stmt_insert->bind_param("issss", $id_pedido_bitacora, $id_pedido, $id_usuario, $estatus, $fecha_registro);
    if (!$stmt_insert->execute()) {
        die('Error al insertar en pedido_bitacora: ' . $stmt_insert->error);
    }
    $stmt_insert->close();
    
    header("Location: detalle.php?id_producto=$id_producto");
} else {
    echo "<p>Error al actualizar el estatus: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();
?>
