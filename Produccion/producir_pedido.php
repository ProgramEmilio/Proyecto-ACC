<?php
include('../BD/ConexionBD.php');
session_start(); // Asegura que se mantenga la sesión

if (!isset($_SESSION['id_usuario'])) {
    die("Error: No hay un usuario en sesión.");
}

if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
$id_productor_usuario = $_SESSION['id_usuario'];

// Verificar si se ha recibido el id_producto
if (isset($_GET['id_producto'])) {
    $id_producto = $_GET['id_producto'];

    // Obtener el id_pedido del producto seleccionado
    $query_pedido = "SELECT id_pedido FROM producto WHERE id_producto = ?";
    if ($stmt = $conn->prepare($query_pedido)) {
        $stmt->bind_param('i', $id_producto);
        $stmt->execute();
        $stmt->bind_result($id_pedido);
        $stmt->fetch();
        $stmt->close();

        if (!empty($id_pedido)) {
            // Actualizar el estatus del pedido a "A enviar"
            $sql_update = "UPDATE pedido SET estatus = 'A enviar' WHERE id_pedido = ?";
            if ($stmt_update = $conn->prepare($sql_update)) {
                $stmt_update->bind_param('s', $id_pedido);
                if ($stmt_update->execute()) {
                    
                    // Generar un ID aleatorio para id_pedido_bitacora
                    $id_pedido_bitacora = rand(100000, 999999);
                    $estatus_pedido = 'A enviar';
                    
                    // Configurar la zona horaria y obtener la fecha actual
                    date_default_timezone_set('America/Mazatlan');
                    $fecha_registro = date('Y-m-d H:i:s');
                    
                    // Insertar en la tabla pedido_bitacora
                    $sql_insert_pedido_bitacora = "INSERT INTO pedido_bitacora (id_pedido_bitacora, id_pedido, id_usuario, estatus_pedido, fecha_registro) 
                                                   VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql_insert_pedido_bitacora);
                    $stmt->bind_param("issss", $id_pedido_bitacora, $id_pedido, $id_productor_usuario, $estatus_pedido, $fecha_registro);
                    if (!$stmt->execute()) {
                        die('Error al insertar en pedido_bitacora: ' . $stmt->error);
                    }
                    $stmt->close();
                    
                    header("Location: productor.php");
                    exit();
                } else {
                    echo "Error al cambiar el estatus: " . $conn->error;
                }
                $stmt_update->close();
            }
        } else {
            echo "No se encontró un pedido asociado a este producto.";
        }
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }
} else {
    echo "ID de producto no recibido.";
}
?>
