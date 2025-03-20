<?php
include('../BD/ConexionBD.php');
session_start();

// Verificar conexión a la base de datos
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = isset($_POST['id_producto']) ? $_POST['id_producto'] : null;
    $calificacion = $_POST['calificacion'];
    $comentario = $_POST['comentario'];

    if ($id_producto !== null) {
        // Preparar la consulta SQL
        $sql = "INSERT INTO comentarios_cliente (id_producto, calificacion, comentario) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sis", $id_producto, $calificacion, $comentario);
            if (mysqli_stmt_execute($stmt)) {
                echo "Comentario guardado exitosamente.";
            } else {
                echo "Error al guardar el comentario: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($conn);
        }
    } else {
        echo "Error: ID de producto no recibido.";
    }

    mysqli_close($conn);
    header("Location: inicio.php");
    exit();
}
?>
